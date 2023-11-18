<?php

namespace App\Http\Controllers;

use App\Models\User as ModelsUser;
use App\OTP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class User extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (isAdmin()) {
            $users = ModelsUser::get();
            return view('dashboard.views.user')->with(['css' => 'user.scss', 'users' => $users]);
        } else {
            return redirect(route('login'));
        }
    }

    public function delete(Request $request)
    {
        $response = array();
        if (sanitize($request->input('action')) == "delete" && !empty(sanitize($request->input('id')))) {
            $delete = ModelsUser::where('id', "=", sanitize($request->input('id')))->delete();
            if ($delete) {
                $response = array(
                    "error" => 0,
                    "msg" => "User deleted"
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

    public function sendOtp(Request $request)
    {

        // if ($request->input('number')) {
        //     $number = str_replace(' ', '', $request->input('number'));
        //     if (!empty($number)) {
        //         $number = $request->input('number');
        //         $number_verify = ModelsUser::where('number', '=', $number)->get();
        //         if ($number_verify->count() > 0) {
        //             $response['error'] = 1;
        //             $response['message'] = "Mobile number is already used";
        //         } else {
        //             $otp_no = rand(100000, 999999);
        //             $OTP = new OTP();
        //             $otp_response = $OTP->sendOTP($otp_no, $number);

        //             if ($otp_response['error'] == "1") {
        //                 $response['error'] = 1;
        //                 $response['message'] = $otp_response["message"];
        //             } else {
        //                 Session::put('otp', password_hash($otp_no, PASSWORD_BCRYPT));
        //                 Session::put('number', $number);
        //                 $response['error'] = "0";
        //                 $response['message'] = "OTP sent to XXXXXX" . substr($number, -4);
        //             }
        //         }
        //     } else {
        //         $response['error'] = 1;
        //         $response['message'] = "Invalid mobile number";
        //     }
        // }


        if ($request->input('number')) {
            $number = str_replace(' ', '', $request->input('number'));
            if (!empty($number)) {

                $number = $request->input('number');
                $number_verify = ModelsUser::where('number', '=', $number)->get();
                if ($number_verify->count() > 0) {
                    $response['error'] = 1;
                    $response['message'] = "Mobile number is already used";
                } else {
                    //$otp_no = rand(100000, 999999); (Should be uncommented for production)
                    $otp_no = 123; //(Should be removed for production)
                    //$OTP = new OTP;
                    //$otp_response = $OTP->sendOTP($otp_no, $number); (Should be uncommented for production)

                    Session::put('otp', password_hash($otp_no, PASSWORD_BCRYPT));
                    Session::put('number', $number);
                    $response['error'] = 0;
                    $response['message'] = "OTP sent to XXX XXX " . substr($number, -4);
                }
            } else {
                $response['error'] = 1;
                $response['message'] = "Invalid mobile number";
            }
        }

        echo json_encode($response);
    }

    public function verifyOtp(Request $request)
    {

        if ($request->input('otp')) {
            $response = array();
            if (Session::has('otp')) {

                $enteredotp = $request->input('otp');
                $OTP = Session::get('otp');

                if (password_verify($enteredotp, $OTP)) {

                    Session::forget('otp');
                    Session::put('otpverified', 'true');
                    $response = array('error' => 0, 'message' => 'Varified');
                } else {
                    Session::put('otpverified', 'false');
                    $response = array('error' => 1, 'message' => 'Invalid OTP');
                }
            } else {
                $response = array('error' => 1, 'message' => 'Invalid verification attempt');
            }
            echo json_encode($response);
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
