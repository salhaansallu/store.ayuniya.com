<?php

namespace App\Http\Controllers;

use App\Models\address;
use App\Models\cities;
use App\Models\districts;
use App\Models\MainOrders;
use App\Models\provinces;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class account extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        redirectCookie();
        if (Auth::check()) {
            return view('account.my-details')->with(['title' => 'My details | ' . config('app.name'), 'css' => 'account.scss']);
        } else {
            return redirect(route('login'));
        }
    }

    public function details()
    {
        redirectCookie();
        if (Auth::check()) {
            return view('account.my-details')->with(['title' => 'My details | ' . config('app.name'), 'css' => 'account.scss']);
        } else {
            return redirect(route('login'));
        }
    }

    public function address()
    {
        redirectCookie();
        if (Auth::check()) {
            return view('account.address')->with(['title' => 'Address book | ' . config('app.name'), 'css' => 'account.scss']);
        } else {
            return redirect(route('login'));
        }
    }

    public function orders()
    {
        redirectCookie();
        if (Auth::check()) {

            $orders = MainOrders::where("user_id", "=", Auth::user()->id)->get();
            $orders->map(function ($suborders) {
                return $suborders->orders;
            });

            if ($orders->count() == 0) {
                $orders = "No orders";
            }

            return view('account.orders')->with(['title' => 'Orders | ' . config('app.name'), 'css' => 'account.scss', 'orders' => $orders]);
        } else {
            return redirect(route('login'));
        }
    }

    public function changePassword()
    {
        redirectCookie();
        if (Auth::check()) {
            return view('account.password')->with(['title' => 'Change account password | ' . config('app.name'), 'css' => 'account.scss']);
        } else {
            return redirect(route('login'));
        }
    }

    public function updateDetails(Request $request)
    {
        $data = array();
        if ($request->input('action') == "personalUpdate" && $request->input('username') && $request->input('email')) {
            if (!is_numeric($request->input('username'))) {
                if (filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
                    $user_update = User::where("id", "=", Auth::user()->id)->update(["name" => $request->input('username'), "email" => $request->input('email')]);
                    if ($user_update) {
                        $data = array(
                            'error' => 0,
                            'msg' => 'Details updated'
                        );
                    } else {
                        $data = array(
                            'error' => 1,
                            'msg' => 'Sorry something went wrong'
                        );
                    }
                } else {
                    $data = array(
                        'error' => 1,
                        'msg' => 'Please enter a valid email'
                    );
                }
            } else {
                $data = array(
                    'error' => 1,
                    'msg' => 'Username can only contain alphabetic characters'
                );
            }
        } elseif ($request->input('action') == "updatenumber") {
            if (Session::has('otpverified') && Session::get('otpverified') == true) {
                $number = sanitize(Session::get('number'));
                $user_update = User::where("id", "=", Auth::user()->id)->update(["number" => $number]);
                if ($user_update) {
                    $data = array(
                        'error' => 0,
                        'msg' => 'Number updated'
                    );
                } else {
                    $data = array(
                        'error' => 1,
                        'msg' => 'Sorry something went wrong'
                    );
                }
            } else {
                $data = array(
                    'error' => 1,
                    'msg' => 'Mobile number not verified'
                );
            }
        } elseif ($request->input('action') == "billing_update") {

            $address1 = sanitize($request->input('address1'));
            $postal = sanitize($request->input('postal'));
            $city = sanitize($request->input('city'));
            $country = sanitize($request->input('country'));
             
            if (!empty($address1) && !empty($postal) && !empty($city) && country($country)) {

                if (getAddress('billing')['has']) {
                    $update = address::where("type", "=", "billing")->where("user_id", "=", Auth::user()->id)->update([
                        'address1' => $address1,
                        'postal' => $postal,
                        'city' => $city,
                        'country' => $country
                    ]);
                    if ($update) {
                        $data = array(
                            "error" => 0,
                            "msg" => "Billing address updated",
                        );
                    } else {
                        $data = array(
                            "error" => 1,
                            "msg" => "Sorry something went wrong",
                        );
                    }
                }
                else {
                    $insert = new address();
                    $insert->address1 = $address1;
                    $insert->postal = $postal;
                    $insert->city = $city;
                    $insert->country = $country;
                    $insert->type = "billing";
                    $insert->user_id = Auth::user()->id;
                    if ($insert->save()) {
                        $data = array(
                            "error" => 0,
                            "msg" => "Billing address updated",
                        );
                    } else {
                        $data = array(
                            "error" => 1,
                            "msg" => "Sorry something went wrong",
                        );
                    }
                }
                
            }
            else {
                $data = array(
                    "error" => 1,
                    "msg" => "Every field is required",
                );
            }
            
            
        } elseif ($request->input('action') == "shipping_update") {
            $address1 = sanitize($request->input('address1'));
            $postal = sanitize($request->input('postal'));
            $city = sanitize($request->input('city'));
            $country = sanitize($request->input('country'));
             
            if (!empty($address1) && !empty($postal) && !empty($city) && country($country)) {

                if (getAddress('shipping')['has']) {
                    $update = address::where("type", "=", "shipping")->where("user_id", "=", Auth::user()->id)->update([
                        'address1' => $address1,
                        'postal' => $postal,
                        'city' => $city,
                        'country' => $country
                    ]);
                    if ($update) {
                        $data = array(
                            "error" => 0,
                            "msg" => "Shipping address updated",
                        );
                    } else {
                        $data = array(
                            "error" => 1,
                            "msg" => "Sorry something went wrong",
                        );
                    }
                }
                else {
                    $insert = new address();
                    $insert->address1 = $address1;
                    $insert->postal = $postal;
                    $insert->city = $city;
                    $insert->country = $country;
                    $insert->type = "shipping";
                    $insert->user_id = Auth::user()->id;
                    if ($insert->save()) {
                        $data = array(
                            "error" => 0,
                            "msg" => "Shipping address updated",
                        );
                    } else {
                        $data = array(
                            "error" => 1,
                            "msg" => "Sorry something went wrong",
                        );
                    }
                }
                
            }
            else {
                $data = array(
                    "error" => 1,
                    "msg" => "Every field is required",
                );
            }
        } 
        else {
            $data = array(
                'error' => 1,
                'msg' => 'Invalid request!'
            );
        }
        return response(json_encode($data));
    }

    public function updatePassword(Request $request)
    {
        $response = array();
        if ($request->input('oldPassword') && $request->input('newPassword') && $request->input('cPassword')) {
            if (Hash::check($request->input('oldPassword'), Auth::user()->password)) {

                $number = preg_match('@[0-9]@', $request->input('newPassword'));

                if (!$number || strlen($request->input('newPassword')) < 8) {
                    $response = array(
                        "error" => 1,
                        "msg" => "Password must contain atleast 1 number and minimum of 8 characters",
                    );
                } else {
                    if ($request->input('newPassword') == $request->input('cPassword')) {
                        $passupdate =  User::where("id", "=", Auth::user()->id)->update(['password' => Hash::make($request->input('newPassword'))]);
                        if ($passupdate) {
                            $response = array(
                                "error" => 0,
                                "msg" => "Password updated",
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
                            "msg" => "Passwords do not match",
                        );
                    }
                }
            } else {
                $response = array(
                    "error" => 1,
                    "msg" => "Current password is invalid",
                );
            }
        } else {
            $response = array(
                "error" => 1,
                "msg" => "All fields are required",
            );
        }

        return response(json_encode($response));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
