<?php

namespace App\Http\Controllers;

use App\Models\cart;
use App\Models\varients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(redirectCookie()) {
            if (Auth::check()) {
                $carts = cart::where("user_id", "=", Auth::user()->id)->get();
                if ($carts->count() == 0) {
                    $carts = "No products in your cart";
                }
                return view('cart')->with(['title' => 'Your cart | ' . config('app.name'), 'css' => 'cart.scss', 'carts' => $carts]);
            } else {
                return redirect(route('login'));
            }
        }
        else {
            redirectCookie();
            if (Auth::check()) {
                $carts = cart::where("user_id", "=", Auth::user()->id)->get();
                if ($carts->count() == 0) {
                    $carts = "No products in your cart";
                }
                return view('cart')->with(['title' => 'Your cart | ' . config('app.name'), 'css' => 'cart.scss', 'carts' => $carts]);
            } else {
                return redirect(route('login'));
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
        $response = array();

        if (Auth::check()) {
            if ($request->input('action') == "add_cart") {

                $varients = varients::where("sku", "=", $request->input('sku'))->get();
                foreach ($varients as $var) {
                    if ($var->qty >= $request->input('qty') && $request->input('qty') != "" && is_numeric($request->input('qty'))) {
                        if ($request->input('qty') > 0 && $request->input('qty') != " " && $request->input('qty') != "") {

                            $cartsverify = cart::where('product_id', '=', $request->input('sku'))->where('user_id', '=', Auth::user()->id)->get();

                            if ($cartsverify->count() > 0) {

                                if (($request->input('qty') + $cartsverify[0]->cart_qty) <= $var->qty) {

                                    $cart_update = cart::where("id", "=", $cartsverify[0]->id)->update(['cart_qty' => $request->input('qty') + $cartsverify[0]->cart_qty]);

                                    if ($cart_update) {
                                        $count = cart::where("user_id", "=", Auth::user()->id)->get();
                                        $response = array(
                                            "error" => 0,
                                            "msg" => "Successfully added to cart",
                                            "count" => $count->count(),
                                        );
                                        $response = array(
                                            "error" => 0,
                                            "msg" => "Cart updated successfully",
                                        );
                                    } else {
                                        $response = array(
                                            "error" => 1,
                                            "msg" => "Error while updating cart",
                                        );
                                    }
                                } else {
                                    $response = array(
                                        "error" => 1,
                                        "msg" => "Only " . $var->qty . " Items available",
                                    );
                                }
                            } else {

                                $cart = new cart();
                                $cart->product_id = $request->input('sku');
                                $cart->cart_qty = $request->input('qty');
                                $cart->user_id = Auth::user()->id;
                                if ($cart->save()) {
                                    $count = cart::where("user_id", "=", Auth::user()->id)->get();
                                    $response = array(
                                        "error" => 0,
                                        "msg" => "Successfully added to cart",
                                        "count" => $count->count(),
                                    );
                                } else {
                                    $response = array(
                                        "error" => 1,
                                        "msg" => "Sorry, something went wrong"
                                    );
                                }
                            }
                        } else {
                            $response = array(
                                "error" => 1,
                                "msg" => "Please select atleast 1 quantity"
                            );
                        }
                    } else {
                        $response = array(
                            "error" => 1,
                            "msg" => "Only " . $var->qty . " Items available"
                        );
                    }
                }
            } elseif ($request->input('action') == "update_cart") {

                $varients = varients::where("sku", "=", sanitize($request->input('sku')))->get();
                if ($varients && $varients->count() > 0) {
                    foreach ($varients as $var) {
                        if ($var->qty >= $request->input('qty') && $request->input('qty') != "" && is_numeric($request->input('qty'))) {
                            if ($request->input('qty') > 0 && !empty($request->input('qty') != " " && $request->input('qty'))) {

                                $cartsverify = cart::where('product_id', '=', sanitize($request->input('sku')))->where('user_id', '=', Auth::user()->id)->get();

                                if ($cartsverify && $cartsverify->count() > 0) {
                                    foreach ($cartsverify as $cartitem) {
                                        if ($request->input('qty') <= $var->qty) {

                                            $cart_update = cart::where("id", "=", $cartitem->id)->update(['cart_qty' => sanitize($request->input('qty'))]);

                                            if ($cart_update) {
                                                $response = array(
                                                    "error" => 0,
                                                    "msg" => "Cart updated successfully",
                                                );
                                            } else {
                                                $response = array(
                                                    "error" => 1,
                                                    "msg" => "Error while updating cart",
                                                );
                                            }
                                        } else {
                                            $response = array(
                                                "error" => 1,
                                                "msg" => "Only " . $var->qty . " Items available",
                                            );
                                        }
                                    }
                                } else {

                                    $response = array(
                                        "error" => 1,
                                        "msg" => "Sorry, something went wrong"
                                    );
                                }
                            } else {
                                $response = array(
                                    "error" => 1,
                                    "msg" => "Please select atleast 1 quantity"
                                );
                            }
                        } else {
                            $response = array(
                                "error" => 1,
                                "msg" => "Only " . $var->qty . " Items available"
                            );
                        }
                    }
                } else {
                    $response = array(
                        "error" => 1,
                        "msg" => "Sorry, something went wrong"
                    );
                }
            } elseif ($request->input('action') == "delete_cart" && $request->input('sku')) {
                $varcart = varients::where("sku", "=", sanitize($request->input('sku')))->get();
                if ($varcart && $varcart->count() > 0) {
                    $deletecart = cart::where("product_id", "=", sanitize($request->input('sku')))->where("user_id", "=", Auth::user()->id)->delete();
                    if ($deletecart) {
                        $response = array(
                            "error" => 0,
                            "msg" => "Product deleted from cart"
                        );
                    } else {
                        $response = array(
                            "error" => 1,
                            "msg" => "Sorry, something went wrong"
                        );
                    }
                } else {
                    $response = array(
                        "error" => 1,
                        "msg" => "Sorry, something went wrong"
                    );
                }
            } else {
                $response = array(
                    "error" => 1,
                    "msg" => "Sorry, something went wrong"
                );
            }
        } else {
            $response = array(
                "error" => 1,
                "msg" => "not_loggedin"
            );
        }

        return response(json_encode($response));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(cart $cart)
    {
        //
    }
}
