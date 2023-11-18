<?php

namespace App\Http\Controllers;

use App\Models\address;
use App\Models\MainOrders;
use App\Models\Orders;
use App\Models\products;
use App\Models\User;
use App\Models\varients;
use App\Models\VendorPayments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (isAdmin() || isOrderManager() ||isCustomerCareManager())  {
            return view("dashboard.views.orders")->with(['css' => 'orders.scss']);
        } else {
            return redirect(route('login'));
        }
    }

    public function getOrder(Request $request)
    {
        $response = array();
        $main_order = MainOrders::where("order_number", "=", sanitize($request->input('order_number')))->get();
        $user_id = $main_order[0]->user_id;
        $html = "";
        if ($main_order[0]->status == "pending") {
            $html = '
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <th><button class="red" onclick="updateStatus(' . $main_order[0]->id . ', 0)">Cancel order</button> <button class="green" onclick="updateStatus(' . $main_order[0]->id . ', 1)">Process</button></th>
            </tr>
            ';
        }
        elseif ($main_order[0]->status == "processing") {
            $html = '
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <th><button class="red" onclick="updateStatus(' . $main_order[0]->id . ', 0)">Cancel order</button> <button class="green" onclick="updateStatus(' . $main_order[0]->id . ', 2)">Deliver</button></th>
            </tr>
            ';
        }
        else {
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
        if (sanitize($request->input('action')) == "get_details" && sanitize($request->input('order_number'))) {
            $orders = Orders::where("order_number", "=", sanitize($request->input('order_number')))->get();
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

    function deleteOrder(Request $request)
    {
        if (sanitize($request->input('action')) == "delete" && sanitize($request->input('order_id'))) {

            $main_order = MainOrders::where("id", "=", sanitize($request->order_id));

            if ($main_order->count() > 0 && !empty($main_order->get()[0]->order_number)) {

                $order_number = $main_order->get()[0]->order_number;
                Orders::where("order_number", "=", $order_number)->delete();
                $main_order->delete();
                return response(json_encode(array("error" => 0, "msg" => "Order deleted")));
            } else {
                return response(json_encode(array("error" => 1, "msg" => "Something went wrong")));
            }
        } else {
            return response(json_encode(array("error" => 1, "msg" => "Something went wrong")));
        }
    }

    function updateOrder(Request $request)
    {

        $response = array();
        if (sanitize($request->input('action')) == "update_status" && sanitize($request->input('id')) && sanitize($request->input('status'))) {
            if (sanitize($request->input('status')) == "canceled" || sanitize($request->input('status')) == "delivered" || sanitize($request->input('status')) == "processing" ) {
                $order = MainOrders::where("id", "=", sanitize($request->input('id')));
                if ($order->count() > 0) {
                    $order->update(["status" => sanitize($request->input('status'))]);
                    $pros = Orders::where("order_number", "=", $order->get()[0]->order_number);
                    if (sanitize($request->input('status')) == "processing") {
                        $profit = 0;
                        foreach ($pros->get() as $pro) {
                            $vendor_total = (75 / 100) * $pro->total;
                            $vendor = new VendorPayments();
                            $vendor->total_amount = $vendor_total;
                            $vendor->status = "pending";
                            $vendor->vendor_id = products::where("id", "=", varients::where("sku", "=", $pro->product_id)->get()[0]->pro_id)->get()[0]->vendor;
                            $vendor->save();
                            $profit += $pro->total - $vendor_total;
                        }

                        $order->update(["total_order" => $profit]);
                    }
                    elseif (sanitize($request->input('status')) == "delivered") {
                        $order->update([
                            "courier_name" => sanitize($request->input("courier_name")),
                            "hand_over_date" => date("d/m/Y"),
                            "track_code" => sanitize($request->input("track_code")),
                            "track_link" => sanitize($request->input("track_link")),
                        ]);
                    }
                    if ($order) {
                        $response = array(
                            "error" => 0,
                            "msg" => "Order status updated"
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

            // #Suja 12/06/2023
            // else if (sanitize($request->input('status')) == "pending") {
            //     $order = MainOrders::where("id", "=", sanitize($request->input('id')));
            //     if ($order->count() > 0) {
            //         $order->update(["status" => 'Processing']);
            //     }
            // }
            // #   } else {
            // #      $response = array(
            // #          "error" => 1,
            // #          "msg" => "Something went wrong"
            // #      );
        } //else if (sanitize($request->input('status')) != "pending" && sanitize($request->input('status')) != "delivered" && sanitize($request->input('status')) != "cancelled") {
        //     $order = MainOrders::where("id", "=", sanitize($request->input('id')));
        //     if ($order->count() > 0) {
        //         $order->update(["status" => 'Shipped']);
        //         $order->courier_name = $courierName;
        //         $order->hand_over_date = date("d/m/Y");
        //         $order->track_code = $trackingCode;
        //         $order->track_link = $trackingLink;

        //         $order->save();
        //     }

        // } else {
        //     $response = array(
        //         "error" => 1,
        //         "msg" => "Something went wrong"
        //     );
        // }

        return response(json_encode($response));
    }

    public function printingOrders()
    {
        if ("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] == "http://192.168.1.10/print-orders") {
            if (Auth::check() && isOrderManager()) {
                return view('dashboard.views.order-print')->with(['css' => 'orders.scss', 'orders' => MainOrders::where("print", "=", "")->get()]);
            } else {
                return redirect('/login');
            }
        } else {
            return redirect('/login');
        }
    }

    public function printOrders()
    {
        if ($_SERVER['HTTP_HOST'] == "192.168.1.10") {
            $order = MainOrders::where("print", "=", "")->first();
            if ($order) {
                return response(json_encode(array("error" => 0, "msg" => generateInvioce($order->id))));
            } else {
                return response(json_encode(array("error" => 1, "msg" => "No new orders")));
            }
        }
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
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function show(Orders $orders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function edit(Orders $orders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Orders $orders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orders $orders)
    {
        //
    }
}
