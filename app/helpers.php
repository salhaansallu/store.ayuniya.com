<?php
ob_start();
use App\Models\address;
use App\Models\cart;
use App\Models\Categories;
use App\Models\cities;
use App\Models\districts;
use App\Models\MainOrders;
use App\Models\Orders;
use App\Models\products;
use App\Models\provinces;
use App\Models\SubCategories;
use App\Models\User;
use App\Models\user_appointments;
use App\Models\varients;
use App\Models\VendorPayments;
use App\Models\vendors;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

function currency($price)
{
    if (strpos($price, '.')) {
        return 'LKR ' . round((float)$price, 2);
    } elseif (empty($price) || $price == "0") {
        return "0.00";
    } else {
        return 'LKR ' . $price . '.00';
    }
}

function min_price($prices)
{
    $low_price = $prices[0]->sales_price;
    $amt = $prices[0]->price;
    foreach ($prices as $key => $price) {
        if ($low_price > $price->sales_price) {
            $low_price = $price->sales_price;
            $amt = $price->price;
        }
    }
    return array(currency($low_price), currency($amt));
}

function productURL($pro_id, $pro_name)
{
    return "/product/" . $pro_id . "/" . str_replace(' ', '-', strtolower($pro_name));
}

function categoryURL($cat_id, $cat_name)
{
    return "/category/" . $cat_id . '/' . str_replace(' ', '-', strtolower($cat_name));
}

function validate_image($url)
{
    if (file_exists(public_path('assets/images/products/' . $url))) {
        return asset('assets/images/products/' . $url);
    } else {
        return asset('assets/images/placeholder/placeholder.png');
    }
}

function validateCompanyLogo($url)
{

    if (file_exists(public_path('assets/images/services/booking/' . $url))) {
        return asset('assets/images/services/booking/' . $url);
    } else {
        return asset('assets/images/placeholder/placeholder.png');
    }
}

function getCategories()
{
    $category = Categories::get();
    $category->map(function ($data) {
        return $data->subcategories;
    });

    return $category;
}

function getVendors($id = false)
{
    if ($id == false) {
        $vendor = vendors::get();
        return $vendor;
    } else {
        $vendor = vendors::where("id", "=", $id)->get();
        return $vendor;
    }
}

function getCartCount()
{
    if (Auth::check()) {
        return cart::where("user_id", "=", Auth::user()->id)->count();
    } else {
        return "0";
    }
}

function sanitize($string)
{
    return strip_tags($string);
}

function redirectCookie()
{
    Session::put('redirectAfterLogin', request()->getRequestUri());
}

function getAddress($type)
{
    $address = array();
    if (strtolower($type) == "shipping" || strtolower($type) == "billing") {
        $address = address::where('type', '=', strtolower($type))->where('user_id', '=', Auth::user()->id)->get();
        if (count($address) > 0) {
            $address = array(
                "has" => true,
                "id" => $address[0]->id,
                "address1" => $address[0]->address1,
                "postal" => $address[0]->postal,
                "city" => $address[0]->city,
                "country" => $address[0]->country,
                "type" => $address[0]->type,
                "user_id" => $address[0]->user_id,
                "created_at" => $address[0]->created_at,
                "updated_at" => $address[0]->updated_at,
            );
        } else {
            $address = array(
                "has" => false,
                "id" => "",
                "address1" => "",
                "postal" => "",
                "city" => "",
                "country" => "",
                "type" => "",
                "user_id" => "",
                "created_at" => "",
                "updated_at" => "",
            );
        }
    } else {
        $address = array(
            "has" => false,
            "id" => "Invalid argument syntax",
            "address1" => "Invalid argument syntax",
            "postal" => "Invalid argument syntax",
            "city" => "Invalid argument syntax",
            "country" => "Invalid argument syntax",
            "type" => "Invalid argument syntax",
            "user_id" => "Invalid argument syntax",
            "created_at" => "Invalid argument syntax",
            "updated_at" => "Invalid argument syntax",
        );
    }

    return $address;
}

function getUserAddress($id)
{
    $address = address::where('type', '=', "billing")->where('user_id', '=', $id)->get();
    if (count($address) > 0) {
        $address = array(
            "id" => $address[0]->id,
            "address1" => $address[0]->address1,
            "postal" => $address[0]->postal,
            "city" => $address[0]->city,
            "country" => $address[0]->country,
            "type" => $address[0]->type,
            "user_id" => $address[0]->user_id,
            "created_at" => $address[0]->created_at,
            "updated_at" => $address[0]->updated_at,
        );
    } else {
        $address = array(
            "id" => "",
            "id" => "",
            "address1" => "",
            "postal" => "",
            "city" => "",
            "country" => "",
            "type" => "",
            "user_id" => "",
            "created_at" => "",
            "updated_at" => "",
        );
    }
    return $address;
}

function country($attr) {
    $country = array(
        "Sri Lanka",
        "Canada",
        "Australia",
        "France",
    );

    if ($attr == "get") {
        return $country;
    }
    else {
        if (in_array($attr, $country)) {
            return true;
        }
        else {
            return false;
        }
    }
}

// function validateAddress($province, $district, $city)
// {
//     $arr = array();

//     if (!empty($province)) {

//         $province = provinces::where("name_en", "=", $province)->get();
//         if ($province && count($province) > 0) {

//             if (!empty($district)) {

//                 $district = districts::where("name_en", "=", $district)->get();

//                 if ($district && count($district) > 0) {

//                     if ($district[0]->province_id == $province[0]->id) {

//                         if (!empty($city)) {

//                             $city = cities::where("name_en", "=", $city)->get();

//                             if ($city && count($city) > 0) {

//                                 if ($city[0]->district_id == $district[0]->id) {

//                                     $arr = array(
//                                         "error" => 0,
//                                         "msg" => "success",
//                                     );
//                                 } else {
//                                     $arr = array(
//                                         "error" => 1,
//                                         "msg" => "Invalid city",
//                                     );
//                                 }
//                             } else {
//                                 $arr = array(
//                                     "error" => 1,
//                                     "msg" => "Invalid city",
//                                 );
//                             }
//                         } else {
//                             $arr = array(
//                                 "error" => 1,
//                                 "msg" => "Invalid city",
//                             );
//                         }
//                     } else {
//                         $arr = array(
//                             "error" => 1,
//                             "msg" => "Invalid district",
//                         );
//                     }
//                 } else {
//                     $arr = array(
//                         "error" => 1,
//                         "msg" => "Invalid district",
//                     );
//                 }
//             } else {
//                 $arr = array(
//                     "error" => 1,
//                     "msg" => "Invalid district",
//                 );
//             }
//         } else {
//             $arr = array(
//                 "error" => 1,
//                 "msg" => "Invalid province",
//             );
//         }
//     } else {
//         $arr = array(
//             "error" => 1,
//             "msg" => "Invalid province",
//         );
//     }

//     return $arr;
// }

function orderStatus($value)
{
    if ($value->status == "pending") {
        return "Processing";
    } elseif ($value->status == "delivered") {
        return "Delivered";
    } elseif ($value->status == "canceled") {
        return "Canceled";
    } else {
        return "Invalid argument";
    }
}

function getProducts($sku)
{

    $products = array();

    $varient = varients::where("sku", "=", $sku)->get();

    if ($varient) {
        if ($varient->count() > 0) {
            $product = products::where("id", "=", $varient[0]->pro_id)->get();
            if ($product) {
                if ($product->count() > 0) {

                    $products = array(
                        "error" => 0,
                        "id" => $product[0]->id,
                        "product_name" => $product[0]->product_name,
                        "short_des" => $product[0]->short_des,
                        "long_des" => $product[0]->long_des,
                        "category" => $product[0]->category,
                        "banner" => $product[0]->banner,
                        "created_at" => $product[0]->created_at,
                        "updated_at" => $product[0]->updated_at,
                        "varient" => $varient
                    );
                } else {
                    $products = array(
                        "error" => 1,
                        "id" => "product not found",
                        "product_name" => "product not found",
                        "short_des" => "product not found",
                        "long_des" => "product not found",
                        "category" => "product not found",
                        "banner" => "product not found",
                        "created_at" => "product not found",
                        "updated_at" => "product not found",
                        "varient" => "varient not found",
                    );
                }
            } else {
                $products = array(
                    "error" => 1,
                    "id" => "Something went wrong",
                    "product_name" => "Something went wrong",
                    "short_des" => "Something went wrong",
                    "long_des" => "Something went wrong",
                    "category" => "Something went wrong",
                    "banner" => "Something went wrong",
                    "created_at" => "Something went wrong",
                    "updated_at" => "Something went wrong",
                    "varient" => "Something went wrong",
                );
            }
        } else {
            $products = array(
                "error" => 1,
                "id" => "No varient found",
                "product_name" => "No varient found",
                "short_des" => "No varient found",
                "long_des" => "No varient found",
                "category" => "No varient found",
                "banner" => "No varient found",
                "created_at" => "No varient found",
                "updated_at" => "No varient found",
                "varient" => "No varient found",
            );
        }
    } else {
        $products = array(
            "error" => 1,
            "id" => "Something went wrong",
            "product_name" => "Something went wrong",
            "short_des" => "Something went wrong",
            "long_des" => "Something went wrong",
            "category" => "Something went wrong",
            "banner" => "Something went wrong",
            "created_at" => "Something went wrong",
            "updated_at" => "Something went wrong",
            "varient" => "Something went wrong",
        );
    }
    return $products;
}

function getVendor($id) {
    $vendor = vendors::where("id", "=", $id)->get();
    return $vendor;
}

function getProductCategory($id)
{
    $cat = SubCategories::where("id", "=", $id)->get();
    if ($cat && $cat->count() > 0) {
        return $cat;
    } else {
        return "No category";
    }
}

function orderTotal($status, $data = false)
{
    $totalorders = null;
    if ($data == false) {
        if ($status == "delivered") {
            $totalorders = MainOrders::where("status", "=", $status)->get()->count();
        } elseif ($status == "pending") {
            $totalorders = MainOrders::where("status", "=", $status)->get()->count();
        } elseif ($status == "canceled") {
            $totalorders = MainOrders::where("status", "=", $status)->get()->count();
        } else {
            $totalorders = "Invalid order type";
        }
    } elseif ($data == true) {
        if ($status == "delivered") {
            $totalorders = MainOrders::where("status", "=", $status)->get();
            $totalorders->map(function ($data) {
                return $data->orders;
            });
        } elseif ($status == "pending") {
            $totalorders = MainOrders::where("status", "=", $status)->get();
            $totalorders->map(function ($data) {
                return $data->orders;
            });
        } elseif ($status == "canceled") {
            $totalorders = MainOrders::where("status", "=", $status)->get();
            $totalorders->map(function ($data) {
                return $data->orders;
            });
        } else {
            $totalorders = "Invalid order type";
        }
    } else {
        $totalorders = "Invalid count argument";
    }
    return $totalorders;
}

function get_cart_total($currency = true)
{
    $total = 0.00;
    $carts = DB::select('select * from carts,varients where sku=product_id and user_id=' . Auth::user()->id);

    if (count($carts) > 0) {
        foreach ($carts as $cart) {
            $total += $cart->sales_price * $cart->cart_qty;
        }
    } else {
        $total = "0.00";
    }

    //dd($carts);
    if ($currency == true) {
        return currency($total);
    } else {
        return $total;
    }
}

function getOrderTotal($order, $delivery = 0)
{
    $total = 0;
    foreach ($order as $ordertotal) {
        $total += $ordertotal->total;
    }

    return currency($total+$delivery);
}

function getTotalWeight($orders)
{
    $total = 0;
    foreach ($orders as $order) {
        $total += $order->weight * $order->cart_qty;
    }

    return $total;
}

function getOrders($from, $to, $totalamt = false)
{
    $orders = MainOrders::where("status", "=", "delivered")->whereBetween("created_at", [$from, $to])->get();

    $total = 0;

    if ($orders && $orders->count() > 0) {
        if ($totalamt == true) {
            foreach ($orders as $order) {
                $total += $order->total_order;
            }
            return $total;
        } else {
            return $orders;
        }
    } else {
        return 0;
    }
}

function orderTotalWithON($id) {
    $total = 0;
    $orders = Orders::where("id", "=", $id)->get();
    foreach($orders as $order) {
        $total += $order->total;
    }
    return $total;
}

function getDelWeight($num)
{
    $loop = true;
    $i = 1;
    while ($loop == true) {
        if ($num < 1 || $num <= $i && $num >= $i - ($i - 1)) {
            return $i;
            $loop = false;
        }
        $i++;
    }
}

function getDelivery($products, $qty = false)
{
    $total = 0;
    $weight = 0;

    if ($products == "cart") {
        $product = DB::select("select * from varients, carts where sku=product_id and user_id = " . Auth::user()->id);
        $weight = getDelWeight(getTotalWeight($product));
        $total = $weight <= 1 ? 500 : ($weight-1)*100+500;
    } else {
        if ($qty == false) {
            $weight = getDelWeight(getTotalWeight($products));
            $total = $weight * 500;
            $total = $weight <= 1 ? 500 : ($weight-1)*100+500;
        } else {
            $item = varients::where("sku", "=", $products)->get();
            $weight = getDelWeight($item[0]->weight * $qty);
            $total = $weight <= 1 ? 500 : ($weight-1)*100+500;
        }
    }

    return $total;
}

function appointments($from, $to, $count = false)
{
    if ($count == true) {
        $appointments = user_appointments::where("status", '=', "")->where("user_id", "<>", "")->whereBetween("app_date", [$from, $to])->count();
        return $appointments;
    } elseif ($count == false) {
        $appointments = DB::select("select * from user_appointments, users where user_appointments.user_id = users.id and status='' and app_date between '" . $from . "' and '" . $to . "'");
        //$appointments = DB::table("user_appointments")->join("users", "users.id", "=", "user_appointments.id", $join)->whereBetween("app_date", [$from, $to])->get();
        //dd($appointments);
        return $appointments;
    } else {
        return 'Invalid argument count';
    }
}

function getUserDetails($id)
{
    $user = User::where("id", "=", $id)->get();
    return $user;
}

function getVendorTotal($id)
{
    $total = 0;
    $payments = VendorPayments::where("vendor_id", "=", $id)->where("status", "=", "pending")->get();
    if ($payments->count() > 0) {
        foreach ($payments as $pay) {
            $total += $pay->total_amount;
        }
        $response = array(
            "error" => 0,
            "total" => currency($total),
            "mindate" => $payments->min("created_at"),
            "maxdate" => $payments->max("created_at"),
        );
    } else {
        $response = array(
            "error" => 1,
            "total" => 0,
            "mindate" => 0,
            "maxdate" => 0,
        );
    }
    return $response;
}

function genarateReport($id, $type)
{
    $store = vendors::where("id", "=", $id)->get();

    $html = '
    
    <!DOCTYPE html>
    <html lang="en">
    
    <head>
        <meta charset="utf-8">
        <title>Payment report</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    
    <body>
        <div class="container" style="font-family: arial;">
            <div class="row">
                <!-- BEGIN INVOICE -->
                <div class="col-12">
                    <div class="grid invoice">
                        <div class="grid-body">
                            <div class="invoice-title">
                                <div class="row">
                                    <div class="col-12">
                                        <h2>Payment report</h2>
                                    </div>
                                </div>
                            </div>
                            <hr style="margin-top: 1rem;margin-bottom: 1rem;border: 0;border-top: 1px solid rgba(0, 0, 0, .1);">
                            <div class="row" style="display: flex; justify-content: space-between;">
                                <div class="col-6">
                                    <address>
                                        <strong>From:</strong><br>
                                        ' . config('app.name') . '<br>
                                        <abbr title="Phone">Phone:</abbr> +94 76 1234 5678 <br>
                                        info@email.com
                                    </address>
                                </div>
                                <div class="col-6" style="text-align: right;">
                                    <address>
                                        <strong>To:</strong><br>
                                        ' . $store[0]->company_name . ' (' . $store[0]->store_name . ')<br>
                                        ' . $store[0]->company_address1 . ' <br>
                                        <abbr title="Phone">Phone:</abbr> ' . $store[0]->company_number . '
                                    </address>
                                </div>
                            </div>
                            <div class="row" style="display: flex; justify-content: space-between;">
                                <div class="col-6">
                                    <address>
                                        <strong>Payment Method:</strong><br>
                                        ' . $type . ' <br>
                                        <b>Bank name : </b> ' . $store[0]->bank_name . ' <br>
                                        <b>Bank branch : </b> ' . $store[0]->branch_name . ' <br>
                                        <b>Account name : </b> ' . $store[0]->account_name . ' <br>
                                        <b>Account number : </b> ' . $store[0]->account_number . '
                                    </address>
                                </div>
                                <div class="col-6" style="text-align: right;">
                                    <address>
                                        <strong>Payment Date:</strong><br>
                                        ' . date("d-m-Y") . '
                                    </address>
                                </div>
                            </div>
                            <div class="row">
                                <div>
                                    <h3>PAYMENT SUMMARY</h3>
                                    <table style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <td><strong>#</strong></td>
                                                <td><strong>FROM DATE</strong></td>
                                                <td><strong>TO DATE</strong></td>
                                                <td style="text-align: right;"><strong>TOTAL</strong></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>' . date_format(getVendorTotal($store[0]->id)['mindate'], "d-m-Y") . '</td>
                                                <td>' . date_format(getVendorTotal($store[0]->id)['maxdate'], "d-m-Y") . '</td>
                                                <td style="text-align: right;">' . getVendorTotal($store[0]->id)['total'] . '</td>
                                            </tr>
                                            <tr>
                                                <td>|</td>
                                                <td></td>
                                                <td></td>
                                                <td style="text-align: right;"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td style="text-align: right;"><strong>Paid amount</strong></td>
                                                <td style="text-align: right;"><strong>' . getVendorTotal($store[0]->id)['total'] . '</strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                </td>
                                                <td style="text-align: right;"><strong>Balance</strong></td>
                                                <td style="text-align: right;"><strong>0.00</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END INVOICE -->
            </div>
        </div>
    </body>
    
    <style>
        table, th, td {
      border: 1px solid black;border-collapse: collapse;
    }
    </style>
        
    </html>
    
    ';

    $reportname = str_replace(' ', '-', str_replace('.', '-', $store[0]->store_name)) . '-Report-' . date('d-m-Y') . '-' . rand(0, 99999) . '.pdf';

    $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait')->setOptions(['defaultFont' => 'arial']);
    $pdf->render();
    $path = public_path('reports/' . $reportname);
    file_put_contents($path, $pdf->output());
    return asset('reports/' . $reportname);
}

function generateInvioce($id)
{
    $orders = MainOrders::where("id", "=", $id)->get();

    $html = '
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ayuniya invoice</title>
    </head>
    <body style="font-family: sans-serif;">
        <div class="" style="margin: auto;">
            <div class="" style="margin-bottom: 30px;height: 140px;">
                <div class="" style="float: left;">
                    <div class="" style="color: #a7a7a7; margin-bottom: 7px; font-weight: 600;">To: <span style="color: #64BF47;font-size: 19px;">Salhaan</span></div>
                    <div class="" style="color: #a7a7a7; margin-bottom: 7px; font-weight: 600;"><span>'. $orders[0]->ship_address .'</span></div>
                    <div class="" style="color: #a7a7a7; margin-bottom: 7px; font-weight: 600;"><span>'. getUserDetails($orders[0]->user_id)[0]['number'] .'</span></div>
                </div>
                <div class="" style="float: right;">
                    <div class="" style="color: #a7a7a7; margin-bottom: 7px; font-weight: bold;font-size: 19px;">Invoice</div>
                    <div class="" style="color: #a7a7a7; margin-bottom: 7px; font-weight: 600;"><span>ID: #'.$orders[0]->order_number.'</span></div>
                    <div class="" style="color: #a7a7a7; margin-bottom: 7px; font-weight: 600;"><span>Order date: '. date("d-m-Y", strtotime($orders[0]->created_at)) .'</span></div>
                </div>
            </div>

            <table style="width: 100%; margin-bottom: 20px;border-bottom: #dddddd 1px solid;">
                <tr style="background-color: #64BF47;">
                    <td style="padding: 7px; color: #fff;">#</td>
                    <td style="padding: 7px; color: #fff;">Description</td>
                    <td style="padding: 7px; color: #fff;">Qty</td>
                    <td style="padding: 7px; color: #fff;">Unit Price</td>
                    <td style="padding: 7px; color: #fff;">Amount</td>
                </tr>';
                $sub_orders = Orders::where("order_number", "=", $orders[0]->order_number)->get();
                foreach ($sub_orders as $count => $order) {
                    $html .= '
                        <tr style="color: #575757;">
                            <td style="padding: 10px;">'. $count+1 .'</td>
                            <td style="padding: 10px;">'. getProducts($order->product_id)['product_name'] .' ('. getProducts($order->product_id)['varient'][0]['v_name'] .')</td>
                            <td style="padding: 10px;">'. $order->qty .'</td>
                            <td style="padding: 10px;">'. currency($order->total/$order->qty) .'</td>
                            <td style="padding: 10px;">'. currency($order->total) .'</td>
                        </tr>
                    ';
                }

                $html .= '

                        </table>

                        <div class="" style="color: #575757;border-bottom: #dddddd 1px solid;padding: 20px 0;">
                            <div class="" style="height: 30px;"><div style="width: 100px;float: left;">Sub Total</div> <div style="text-align: left;">'. getOrderTotal($sub_orders) .'</div></div>
                            <div class="" style="height: 30px;"><div style="width: 100px;float: left;">Delivery</div> <div style="text-align: left;">'. currency($orders[0]->delivery_charge) .'</div></div>
                            <div class="" style="height: 30px;"><div style="width: 100px;float: left;">Total</div> <div style="text-align: left;color: #000;font-weight: bold;">'. getOrderTotal($sub_orders, $orders[0]->delivery_charge) .'</div></div>
                        </div>
                    </div>
            </body>
            </html>
        
            ';

    $reportname = str_replace(' ', '-', str_replace('.', '-', $orders[0]->order_number)) . '-Invoice-' . date('d-m-Y') . '-' . rand(0, 99999) . '.pdf';

    $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait')->setOptions(['defaultFont' => 'arial']);
    $pdf->render();
    $path = public_path('invoice/' . $reportname);
    file_put_contents($path, $pdf->output());
    MainOrders::where("id", "=", $id)->update(["print" => "printed"]);
    return asset('invoice/' . $reportname);
}

function isAdmin()
{
    if (Auth::check()) {
        if (Auth::user()->is_verified == "admin") {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function isOrderManager()
{
    if (Auth::check()) {
        if (Auth::user()->is_verified == "orders") {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
