<?php

namespace App\Http\Controllers;

use App\Models\address;
use App\Models\Orders;
use App\Models\products;
use App\Models\User;
use App\Models\VendorPayments;
use App\Models\vendors;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VendorPaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (isAdmin()) {
            $reports = vendors::get();
            return view('dashboard.views.payments')->with(['css' => 'payment.scss', 'reports' => $reports]);
        } else {
            return redirect(route('login'));
        }
    }

    public function list()
    {
        if (isAdmin()) {
            $vendors = vendors::get();
            return view('dashboard.views.vendors')->with(['css' => 'products.scss', 'vendors' => $vendors]);
        } else {
            return redirect(route('login'));
        }
    }

    public function register()
    {
        if (isAdmin()) {
            return view('dashboard.views.vendor')->with(['css' => 'vendor.scss']);
        } else {
            return redirect(route('login'));
        }
    }

    public function registerVendor(Request $request)
    {
        $response = array();
        $vendorver = vendors::where("store_name", "=", $request->input('s_name'));
        if ($vendorver->count() > 0) {
            $response = array(
                "error" => 1,
                "msg" => "Store name already taken"
            );
        } else {

            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'number' => 'required',
                'b_type' => 'required',
                's_name' => 'required',
                'license' => 'required',
                'registration_number' => 'required',
                'products' => 'required',
                'bank_type' => 'required',
                'bankname' => 'required',
                'branchname' => 'required',
                'accountname' => 'required',
                'accountnumber' => 'required',
                'password' => 'required',
            ]);

            if ($validate->fails()) {
                $response = array(
                    "error" => 1,
                    "msg" => "Please fill all required fields"
                );
            } else {

                if (vendors::where('email', $request->input('email'))->first()->count() > 0) {
                    return response(json_encode(array(
                        "error" => 1,
                        "msg" => "Email already exists"
                    )));
                }

                $vendor = vendors::insert([
                    "company_name" => $request->input('name'),
                    "company_email" => $request->input('email'),
                    "company_number" => $request->input('number'),
                    "company_fax" => $request->input('fax'),
                    "company_address1" => $request->input('address1'),
                    "company_address2" => $request->input('address2'),
                    "company_website" => $request->input('website'),
                    "business_type" => $request->input('b_type'),
                    "store_name" => $request->input('s_name'),
                    "license" => $request->input('license'),
                    "registration" => $request->input('registration_number'),
                    "nop" => $request->input('products'),
                    "payment_type" => $request->input('bank_type'),
                    "bank_name" => $request->input('bankname'),
                    "branch_name" => $request->input('branchname'),
                    "account_name" => $request->input('accountname'),
                    "account_number" => $request->input('accountnumber'),
                    "password" => Hash::make($request->input('password')),
                    "verify" => "active"
                ]);

                if ($vendor) {
                    $response = array(
                        "error" => 0,
                        "msg" => "Vendor registered"
                    );
                } else {
                    $response = array(
                        "error" => 1,
                        "msg" => "Something went wrong"
                    );
                }
            }
        }

        return response(json_encode($response));
    }

    public function payVendor(Request $request)
    {
        $response = array();
        if ($request->input('action') == "pay") {
            $vendor = sanitize($request->input('vendor'));
            $payment_type = sanitize($request->input('payment_type'));

            if ($payment_type == "Cheque" && sanitize($request->input('cheque_no'))) {
                $cheque_no = sanitize($request->input('cheque_no'));
                $report = genarateReport($vendor, $payment_type);
                $pay = VendorPayments::where("vendor_id", "=", $vendor)->where("status", "=", "pending")->update(["status" => $cheque_no]);
                if ($pay) {

                    $response = array(
                        "error" => 0,
                        "msg" => "Successfully paid",
                        "url" => $report
                    );
                } else {
                    $response = array(
                        "error" => 1,
                        "msg" => "Sorry something went wrong",
                    );
                }
            } elseif ($payment_type == "Bank transfer") {
                $report = genarateReport($vendor, $payment_type);
                $pay = VendorPayments::where("vendor_id", "=", $vendor)->where("status", "=", "pending")->update(["status" => "paid"]);
                if ($pay) {
                    $response = array(
                        "error" => 0,
                        "msg" => "Successfully paid",
                        "url" => $report
                    );
                } else {
                    $response = array(
                        "error" => 1,
                        "msg" => "Sorry something went wrong",
                    );
                }
            } elseif ($payment_type == "cancel") {
                $pay = VendorPayments::where("vendor_id", "=", $vendor)->where("status", "=", "pending")->update(["status" => "canceled"]);
                if ($pay) {
                    $response = array(
                        "error" => 0,
                        "msg" => "Successfully cancelled",
                        "url" => "null",
                    );
                } else {
                    $response = array(
                        "error" => 1,
                        "msg" => "Sorry something went wrong",
                    );
                }
            } else {
                $response = array(
                    "error" => 1,
                    "msg" => "Sorry something went wrong",
                );
            }
        } else {
            $response = array(
                "error" => 1,
                "msg" => "Sorry something went wrong",
            );
        }

        return response(json_encode($response));
    }

    function verifyPage($id)
    {
        $vendor = vendors::where("verify", "=", sanitize($id));
        if ($vendor->count() > 0 && $vendor->where("store_name", "=", "")->count() > 0) {
            return view('dashboard.views.vendorreg')->with(['css' => 'vendor.scss', "_code" => $id]);
        } else {
            return view('dashboard.views.vendorreg')->with(['css' => 'vendor.scss', "error" => "Invalid register"]);
        }
    }

    function verify(Request $request)
    {
        $response = array("error" => 1, "msg" => "Something went wrong");

        $vendor = vendors::where("verify", "=", sanitize($request->input('_code')));

        if ($vendor->count() > 0 && $vendor->where("store_name", "=", "")->count() > 0) {
            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'number' => 'required',
                'b_type' => 'required',
                's_name' => 'required',
                'license' => 'required',
                'registration_number' => 'required',
                'products' => 'required',
                'bank_type' => 'required',
                'bankname' => 'required',
                'branchname' => 'required',
                'accountname' => 'required',
                'accountnumber' => 'required',
                'password' => 'required',
            ]);

            if ($validate->fails()) {
                $response = array("error" => 1, "msg" => "Please fill all required fields");
            } else {
                if (vendors::where("store_name", "=", sanitize($request->input('s_name')))->count() == 0) {

                    if (vendors::where('email', $request->input('email'))->first()->count() > 0) {
                        return response(json_encode(array(
                            "error" => 1,
                            "msg" => "Email already exists"
                        )));
                    }

                    $create = $vendor->update([
                        "company_name" => sanitize($request->input('name')),
                        "company_email" => sanitize($request->input('email')),
                        "company_number" => sanitize($request->input('number')),
                        "company_fax" => sanitize($request->input('fax')),
                        "company_address1" => sanitize($request->input('address1')),
                        "company_address2" => sanitize($request->input('address2')),
                        "company_website" => sanitize($request->input('website')),
                        "business_type" => sanitize($request->input('b_type')),
                        "store_name" => sanitize($request->input('s_name')),
                        "license" => sanitize($request->input('license')),
                        "registration" => sanitize($request->input('registration_number')),
                        "nop" => sanitize($request->input('products')),
                        "payment_type" => sanitize($request->input('bank_type')),
                        "bank_name" => sanitize($request->input('bankname')),
                        "branch_name" => sanitize($request->input('branchname')),
                        "account_name" => sanitize($request->input('accountname')),
                        "account_number" => sanitize($request->input('accountnumber')),
                        "password" => Hash::make($request->input('password')),
                    ]);

                    if ($create) {
                        $response = array(
                            "error" => 0,
                            "msg" => "Registered successfuly"
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
                        "msg" => "Store name already taken"
                    );
                }
            }
        } else {
            $response = array(
                "error" => 1,
                "msg" => "Invalid registration"
            );
        }

        return response(json_encode($response));
    }

    function GenerateCode()
    {
        $code = 'AYUNIYA_V_' . rand(1111, 99999) . time();
        $vendor = new vendors();
        $vendor->verify = $code;
        if ($vendor->save()) {
            return response(json_encode(array(
                "error" => 0,
                "vendorCode" => $code
            )));
        }
        return response(json_encode(array(
            "error" => 1,
            "msg" => "Sorry, something went wrong",
        )));
    }

    public function login(Request $request)
    {
        $email = sanitize($request->input('email'));
        $password = sanitize($request->input('password'));

        $verify = vendors::where('company_email', $email)->get();
        if ($verify->count() > 0) {
            if (Hash::check($password, $verify[0]->password)) {
                Cookie::queue('__vendor', Crypt::encrypt($email), 864000);
                return redirect('/vendor/dashboard');
            }
        }

        return redirect()->back()->withErrors(['email' => 'Invalid email or password']);
    }

    static function getOrders($from, $to, $totalamt = false)
    {
        $orders = DB::select('select *, orders.total, orders.qty from main_orders,orders,products,varients where main_orders.order_number = orders.order_number and orders.product_id=varients.sku and varients.pro_id=products.id and products.vendor = "'. Vendor()->id .'" and orders.status="delivered" and main_orders.created_at between "'. date('Y-m-d h:i:s', strtotime($from)) .'" and "'. date('Y-m-d h:i:s', strtotime($to)) .'"');

        $total = 0;

        if ($orders && count($orders) > 0) {
            if ($totalamt == true) {
                foreach ($orders as $order) {
                    $total += (float)$order->total * (float)$order->qty;
                }
                return $total;
            } else {
                return $orders;
            }
        } else {
            return 0;
        }
    }

    public function dashboard(Request $request)
    {
        if (isVendor()) {
            $jan = $this->getOrders(date("Y") . "-1-1", date("Y") . "-1-31", true);
            $feb = $this->getOrders(date("Y") . "-2-1", date("Y") . "-2-28", true);
            $mar = $this->getOrders(date("Y") . "-3-1", date("Y") . "-3-31", true);
            $apr = $this->getOrders(date("Y") . "-4-1", date("Y") . "-4-30", true);
            $may = $this->getOrders(date("Y") . "-5-1", date("Y") . "-5-31", true);
            $jun = $this->getOrders(date("Y") . "-6-1", date("Y") . "-6-30", true);
            $jul = $this->getOrders(date("Y") . "-7-1", date("Y") . "-7-31", true);
            $aug = $this->getOrders(date("Y") . "-8-1", date("Y") . "-8-31", true);
            $sep = $this->getOrders(date("Y") . "-9-1", date("Y") . "-9-30", true);
            $oct = $this->getOrders(date("Y") . "-10-1", date("Y") . "-10-31", true);
            $nov = $this->getOrders(date("Y") . "-11-1", date("Y") . "-11-30", true);
            $dec = $this->getOrders(date("Y") . "-12-1", date("Y") . "-12-31", true);

            $sales = array(
                "jan" => json_encode($jan),
                "feb" => json_encode($feb),
                "mar" => json_encode($mar),
                "apr" => json_encode($apr),
                "may" => json_encode($may),
                "jun" => json_encode($jun),
                "jul" => json_encode($jul),
                "aug" => json_encode($aug),
                "sep" => json_encode($sep),
                "oct" => json_encode($oct),
                "nov" => json_encode($nov),
                "dec" => json_encode($dec),
            );

            $low_stock = DB::select("select * from products, varients where products.id=varients.pro_id and qty <= 3 and vendor='" . Vendor()->id . "'");

            //dd($appointments);
            return view('site-vendors.dashboard')->with(['css' => 'index.scss', 'sales' => $sales, 'low_stock' => $low_stock]);
        } else {
            return redirect('/vendor/login');
        }
    }

    public function products()
    {
        if (isVendor()) {
            if (isset($_GET['edit'])) {
                $products = products::where('vendor', Vendor()->id)->orderBy("id", "DESC")->paginate(25);
                $products->map(function ($subvarient) {
                    return $subvarient->varient;
                });

                $editproducts = products::where('vendor', Vendor()->id)->where("id", "=", sanitize($_GET['edit']))->get();
                $editproducts->map(function ($subvarient) {
                    return $subvarient->varient;
                });

                return view('site-vendors.add_products')->with(['css' => 'products.scss', 'products' => $products, 'editproducts' => $editproducts]);
            } else {
                $products = products::where('vendor', Vendor()->id)->orderBy("id", "DESC")->paginate(25);
                $products->map(function ($subvarient) {
                    return $subvarient->varient;
                });
                return view('site-vendors.add_products')->with(['css' => 'products.scss', 'products' => $products]);
            }
        }
        else {
            return redirect(route('login'));
        }
    }

    public function orders() {
        if (isVendor()) {
            return view('site-vendors.orders')->with(['css' => 'orders.scss']);
        }
    }

    public function getVendorOrder(Request $request) {
        $response = array();
        $main_order = Orders::where("id", "=", Crypt::decrypt(sanitize($request->input('order'))))->get();

        if ($main_order->count() <= 0) {
            $response = array(
                "error" => 1,
                "msg" => "Sorry something went wrong"
            );
            return response(json_encode($response));
        }

        $user_id = $main_order[0]->user_id;
        $html = "";
        if ($main_order[0]->status == "pending") {
            $html = '
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <th><button class="red" onclick="updateStatus(&apos;' . Crypt::encrypt($main_order[0]->id) . '&apos;, 0)">Cancel order</button> <button class="green" onclick="updateStatus(&apos;' . Crypt::encrypt($main_order[0]->id) . '&apos;, 1)">Process</button></th>
            </tr>
            ';
        } elseif ($main_order[0]->status == "processing") {
            $html = '
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <th><button class="red" onclick="updateStatus(&apos;' . Crypt::encrypt($main_order[0]->id) . '&apos;, 0)">Cancel order</button> <button class="green" onclick="updateStatus(&apos;' . Crypt::encrypt($main_order[0]->id) . '&apos;, 2)">Deliver</button></th>
            </tr>
            ';
        } else {
            $html = '
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <th></th>
            </tr>
            ';
        }
        if (sanitize($request->input('action')) == "get_details" && sanitize($request->input('order'))) {
            $orders = Orders::where("id", "=", Crypt::decrypt(sanitize($request->input('order'))))->get();
            if ($orders && $orders->count() > 0) {

                foreach ($orders as $order) {
                    $html .= '

                        <tr>
                            <td>
                                <div class="pro_details">
                                    <div class="img">
                                        <img src="' . validate_image(getProducts($order->product_id)['varient'][0]['image_path']) . '" alt="">
                                    </div>
                                    <div class="pro_name">
                                        <div class="name">' . getProducts($order->product_id)['product_name'] . '</div>
                                        <div class="cat">' . getProductCategory(getProducts($order->product_id)['category'])[0]['sub_category_name'] . '</div>
                                        <div class="sku">' . $order->product_id . '</div>
                                        <div class="variant">' . getProducts($order->product_id)['varient'][0]['v_name'] . '</div>
                                    </div>
                                </div>
                            </td>
                            <td>' . currency($order->total / $order->qty) . '</td>
                            <td>' . $order->qty . '</td>
                            <td>' . currency($order->total) . '</td>
                            <td></td>
                        </tr>

                    ';
                }

                $user = User::where("id", "=", $user_id)->get();
                $billaddress = address::where("user_id", "=", $user_id)->where("type", "=", "billing")->get();

                $response = array(
                    "error" => 0,
                    "data" => $html,
                    "user_details" => $user,
                    "billaddress" => $billaddress,
                    "orders" => $main_order[0]->ship_address
                );
            } else {
                $response = array(
                    "error" => 1,
                    "msg" => "Sorry something went wrong"
                );
            }
        } else {
            $response = array(
                "error" => 1,
                "msg" => "Sorry something went wrong"
            );
        }

        return response(json_encode($response));
    }

    public function deleteOrder(Request $request) {
        if (sanitize($request->input('action')) == "delete" && sanitize($request->input('order_id'))) {

            $main_order = Orders::where("id", "=", Crypt::decrypt(sanitize($request->order_id)));

            if ($main_order->count() > 0 && $main_order->delete()) {
                return response(json_encode(array("error" => 0, "msg" => "Order deleted")));
            } else {
                return response(json_encode(array("error" => 1, "msg" => "Something went wrong")));
            }
        } else {
            return response(json_encode(array("error" => 1, "msg" => "Something went wrong")));
        }
    }

    public function updateOrder(Request $request) {
        $response = array();
        if (sanitize($request->input('action')) == "update_status" && sanitize($request->input('id')) && sanitize($request->input('status'))) {
            if (sanitize($request->input('status')) == "canceled" || sanitize($request->input('status')) == "delivered" || sanitize($request->input('status')) == "processing") {
                $order = Orders::where("id", "=", Crypt::decrypt(sanitize($request->input('id'))));
                if ($order->count() > 0) {
                    $order->update(["status" => sanitize($request->input('status'))]);
                    if ($order) {
                        $response = array(
                            "error" => 0,
                            "msg" => "Order updated"
                        );
                    } else {
                        $response = array(
                            "error" => 1,
                            "msg" => "Something went wrong"
                        );
                    }
                } else {
                    $response = array(
                        "error" => 0,
                        "msg" => "Order already updated or not available"
                    );
                }
            }
        }

        return response(json_encode($response));
    }

    public function logout() {
        if (isVendor()) {
            if (vendorLogout()) {
                return redirect('/');
            }
            return redirect()->back();
        }
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VendorPayments  $vendorPayments
     * @return \Illuminate\Http\Response
     */
    public function show(VendorPayments $vendorPayments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VendorPayments  $vendorPayments
     * @return \Illuminate\Http\Response
     */
    public function edit(VendorPayments $vendorPayments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VendorPayments  $vendorPayments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VendorPayments $vendorPayments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VendorPayments  $vendorPayments
     * @return \Illuminate\Http\Response
     */
    public function destroy(VendorPayments $vendorPayments)
    {
        //
    }
}
