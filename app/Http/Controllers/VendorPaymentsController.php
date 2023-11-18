<?php

namespace App\Http\Controllers;

use App\Models\VendorPayments;
use App\Models\vendors;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
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
            return view('dashboard.views.vendors')->with(['css' => 'products.scss', 'vendors'=>$vendors]);
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
            ]);

            if ($validate->fails()) {
                $response = array(
                    "error" => 1,
                    "msg" => "Please fill all required fields"
                );
            }
            else {
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
            return view('dashboard.views.vendorreg')->with(['css' => 'vendor.scss', "_code"=>$id]);
        }
        else {
            return view('dashboard.views.vendorreg')->with(['css' => 'vendor.scss', "error"=>"Invalid register"]);
        }
    }

    function verify(Request $request)
    {
        $response = array("error" => 1, "msg" => "Something went wrong");

        $vendor = vendors::where("verify", "=", sanitize($request->input('_code')));

        if ($vendor->count() > 0 && $vendor->where("store_name", "=", "")->count() > 0) {
            $validate = Validator::make($request->all(), [
                'name' => 'required',
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
            ]);

            if ($validate->fails()) {
                $response = array("error" => 1, "msg" => "Please fill all required fields");
            } else {
                if (vendors::where("store_name", "=", sanitize($request->input('s_name')))->count() == 0) {

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
                        "account_number" => sanitize($request->input('accountnumber'))
                    ]);
    
                    if ($create) {
                        $response = array(
                            "error" => 0,
                            "msg" => "Registered successfuly"
                        );
                    }
                    else {
                        $response = array(
                            "error" => 1,
                            "msg" => "Something went wrong"
                        );
                    }
                }
                else {
                    $response = array(
                        "error" => 1,
                        "msg" => "Store name already taken"
                    );
                }
            }
        }
        else {
            $response = array(
                "error" => 1,
                "msg" => "Invalid registration"
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
