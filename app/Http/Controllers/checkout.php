<?php



namespace App\Http\Controllers;
use GuzzleHttp\Client;
use App\Models\address;
use App\Models\cart;
use App\Models\currency;
use App\Models\MainOrders;
use App\Models\Orders;
use App\Models\products;
use App\Models\provinces;
use App\Models\varients;
use App\Models\VendorPayments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class checkout extends Controller
{
    public function index(Request $request)
    {
        redirectCookie();
        if (Auth::check()) {
            $products = DB::select("select * from varients,carts,products where carts.product_id=varients.sku and varients.pro_id=products.id and carts.user_id=".Auth::user()->id);
            if (count($products) > 0) {
                return view('checkout')->with(['title' => 'Checkout | ' . config('app.name'), 'css' => 'checkout.scss', 'products' => $products]);
            } else {
                return redirect('shop');
            }
        } else {
            return redirect(route('login'));
        }



    }
    public function checkAddress()
    {
        $userId = Auth::id(); // Get the currently logged-in user's ID

        // Check if the user's ID exists in the addresses table
        $addressExists = Address::where('user_id', $userId)->exists();

        return response()->json(['addressExists' => $addressExists]);
    }





    public function buyNow(Request $request)
    {
        if (Auth::check()) {
            if (sanitize($request->input('sku')) && sanitize($request->input('qty'))) {
                $sku = sanitize($request->input('sku'));
                $qty = sanitize($request->input('qty'));
                $products = DB::select("select * from varients,products where varients.pro_id=products.id and sku='" . $sku . "'");
                if (count($products) > 0) {
                    if ($products[0]->qty < $qty) {
                        $qty = $products[0]->qty;
                    }
                    $province = provinces::get();
                    return view('checkout')->with(['title' => 'Checkout | ' . config('app.name'), 'css' => 'checkout.scss', 'products' => $products, 'province' => $province, 'qty' => $qty]);
                } else {
                    return redirect('shop');
                }
            } else {
                return redirect()->back();
            }
        } else {
            return redirect(route('login'));
        }
    }

    public function confirmCheckout(Request $request)
    {
        $response = array("error" => 1, "msg" => "Something went wrong");
        if (sanitize($request->input('action')) && sanitize($request->input('action')) == 'confirm_checkout') {
            if (!empty(sanitize($request->input('address1'))) && !empty(sanitize($request->input('postal'))) && !empty(sanitize($request->input('city'))) && !empty(sanitize($request->input('country')))) {
                $sku = sanitize($request->input('sku'));
                $qty = sanitize($request->input('qty'));
                $address1 = sanitize($request->input('address1'));
                $postal = sanitize($request->input('postal'));
                $city = sanitize($request->input('city'));
                $country = sanitize($request->input('country'));
                $orderno = MainOrders::latest('id')->first();
                $delivery = getDelivery($sku, $qty);

                if ($orderno) {
                    $orderno = $orderno->order_number + 1;
                } else {
                    $orderno = 1001;
                }

                if (country($country)) {
                    $checkout = new MainOrders();
                    $checkout->order_number = $orderno;
                    $checkout->user_id = Auth::user()->id;
                    $checkout->bill_address = getAddress("billing")['id'];
                    $checkout->ship_address = $address1 . " <br /> " . $city . " <br /> " . $postal . " <br /> " . $country;
                    $checkout->status = "pending";
                    if ($country == "Sri Lanka") {
                        $checkout->delivery_charge = $delivery;
                    }
                    else {
                        $checkout->delivery_charge = (float)currency::where("type", "=", "USD")->get()[0]->rate*65;
                    }
                    $checkout->total_order = 0.00;
                    if ($checkout->save()) {
                        $order = new Orders();
                        $order->order_number = $orderno;
                        $order->product_id = $sku;
                        $order->qty = $qty;
                        $order->user_id = Auth::user()->id;
                        $order->total = varients::where("sku", "=", $sku)->get()[0]->sales_price * $qty;
                        if ($order->save()) {

                            $item_detail = varients::where("sku", "=", $sku)->get();
                            varients::where("sku", "=", $sku)->update(["qty" => $item_detail[0]->qty - $qty]);

                            $response = array(
                                "error" => 0,
                                "msg" => "confirmed",
                                "orderno" => $orderno,
                                "region" => $country
                            );
                        } else {
                            $response = array(
                                "error" => 1,
                                "msg" => "Something went wrong"
                            );
                        }
                    } else {
                        $response = array(
                            "error" => 1,
                            "msg" => "Something went wrong"
                        );
                    }
                } else {
                    $response = array(
                        "error" => 1,
                        "msg" => "Something went wrong"
                    );
                }
            } else {
                $response = array(
                    "error" => 1,
                    "msg" => "Please fill all delivery address fields"
                );
            }
        } elseif (sanitize($request->input('action')) && sanitize($request->input('action')) == 'cart_checkout') {

            if (!empty(sanitize($request->input('address1'))) && !empty(sanitize($request->input('postal'))) && !empty(sanitize($request->input('city'))) && !empty(sanitize($request->input('country')))) {
                $address1 = sanitize($request->input('address1'));
                $postal = sanitize($request->input('postal'));
                $city = sanitize($request->input('city'));
                $country = sanitize($request->input('country'));
                $orderno = MainOrders::latest('id')->first();
                $cart = cart::where("user_id", "=", Auth::user()->id)->get();
                $delivery = getDelivery("cart");

                if ($orderno) {
                    $orderno = $orderno->order_number + 1;
                } else {
                    $orderno = 1001;
                }

                if (country($country)) {
                    $checkout = new MainOrders();
                    $checkout->order_number = $orderno;
                    $checkout->user_id = Auth::user()->id;
                    $checkout->bill_address = getAddress("billing")['id'];
                    $checkout->ship_address = $address1 . " <br /> " . $city . " <br /> " . $postal . " <br /> " . $country;
                    $checkout->status = "pending";
                    if ($country == "Sri Lanka") {
                        $checkout->delivery_charge = $delivery;
                    }
                    else {
                        $checkout->delivery_charge = (float)currency::where("type", "=", "USD")->get()[0]->rate*65;
                    }
                    $checkout->total_order = 0.00;
                    if ($checkout->save()) {
                        $products = DB::select("select * from carts, varients where sku=product_id and user_id = " . Auth::user()->id);
                        foreach ($products as $pro) {
                            $order = new Orders();
                            $order->order_number = $orderno;
                            $order->product_id = $pro->sku;
                            $order->qty = $pro->cart_qty;
                            $order->user_id = Auth::user()->id;
                            $order->total = (float)$pro->sales_price * (float)$pro->cart_qty;
                            $order->save();

                            varients::where("sku", "=", $pro->sku)->update(["qty" => $pro->qty - $pro->cart_qty]);
                        }
                        cart::where("user_id", "=", Auth::user()->id)->delete();
                        $response = array(
                            "error" => 0,
                            "msg" => "confirmed",
                            "orderno" => $orderno,
                            "region" => $country
                        );
                    }
                }
                else {
                    $response = array(
                        "error" => 1,
                        "msg" => "Something went wrong"
                    );
                }
            } else {
                $response = array(
                    "error" => 1,
                    "msg" => "Please fill all delivery address fields"
                );
            }
        } else {
            $response = array(
                "error" => 1,
                "msg" => "Something went wrong"
            );
        }

        return response(json_encode($response));
    }

    public function getTotal(Request $request) {
        $response = json_encode(array(
            "error"=>1,
            "msg"=>"Sorry! Something went wrong"
        ));
        $country = sanitize($request->input('country'));
        if (sanitize($request->input("get_total")) == "cart" && country($country)) {
            $total = get_cart_total(false);
            $products = DB::select("select * from varients,carts,products where carts.product_id=varients.sku and varients.pro_id=products.id");

            if ($country == "Sri Lanka") {
                $response = json_encode(array(
                    "error"=>0,
                    "subtotal"=>currency($total),
                    "del"=> currency(getDelivery($products)),
                    "total"=> currency((float)getDelivery($products)+(float)$total),
                ));
            }
            else {
                if (getTotalWeight($products) <= 2) {
                    if (getTotalWeight($products) >= 0.5) {
                        $usdtotal = (float)$total/(float)currency::where("type", "=", "USD")->get()[0]->rate;
                        $response = json_encode(array(
                            "error"=>0,
                            "subtotal"=>"USD ".round((float)$usdtotal, 2),
                            "del"=> "USD 65",
                            "total"=> "USD ".round((float)$usdtotal+65, 2),
                        ));
                    }
                    else {
                        $response = json_encode(array(
                            "error"=>1,
                            "msg"=>"Order should be more than 0.5KG to be delivered"
                        ));
                    }
                }
                else {
                    $response = json_encode(array(
                        "error"=>1,
                        "msg"=>"Orders more than 2KG can only be delivered to Sri Lanka"
                    ));
                }
            }
        }
        elseif (sanitize($request->input("get_total")) == "product" && country($country)) {
            $sku = sanitize($request->input("sku"));
            $qty = sanitize($request->input("qty"));
            $products = varients::where("sku", "=", $sku)->get();
            $total = $products[0]->sales_price * $qty;

            if ($country == "Sri Lanka") {
                $response = json_encode(array(
                    "error"=>0,
                    "subtotal"=>currency($total),
                    "del"=> currency(getDelivery($products)),
                    "total"=> currency((float)getDelivery($products)+(float)$total),
                ));
            }
            else {
                if (($products[0]->weight * $qty) <= 2) {
                    if (($products[0]->weight * $qty) >= 0.5) {
                        $usdtotal = (float)$total/(float)currency::where("type", "=", "USD")->get()[0]->rate;
                        $response = json_encode(array(
                            "error"=>0,
                            "subtotal"=>"USD ".round((float)$usdtotal, 2),
                            "del"=> "USD 65",
                            "total"=> "USD ".round((float)$usdtotal+65, 2),
                        ));
                    }
                    else {
                        $response = json_encode(array(
                            "error"=>1,
                            "msg"=>"Order should be more than 0.5KG to be delivered"
                        ));
                    }
                }
                else {
                    $response = json_encode(array(
                        "error"=>1,
                        "msg"=>"Orders more than 2KG can only be delivered to Sri Lanka"
                    ));
                }
            }
        }

        return $response;
    }
}
