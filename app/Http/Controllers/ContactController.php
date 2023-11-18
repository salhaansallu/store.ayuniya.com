<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function saveContact(ContactRequest $request)
    {
        $appObj = new Contact();


        $appObj->name = $request->name;
        $appObj->email = $request->email;
        $appObj->tp_no = $request->tp_no;
        $appObj->message = $request->message;





        try {
            $data = $request->validated();
            $appObj->save();
            return redirect()->back()->with('message', 'Your Message Sent Successfully');
        } catch (\Exception $ex) {
            return redirect()->back()->with('message', 'somthing went wrong' . $ex);
        }
    }

    public function index()
    {
        if (isAdmin()|| isCustomerCareManager()) {
            $contact = contact::get();
            return view('dashboard.views.contact')->with(['css' => 'user.scss', 'contact' => $contact]);
        } else {
            return redirect(route('login'));
        }
    }





    public function delete(Request $request)
    {
        $response = array();
        if (sanitize($request->input('action')) == "delete" && !empty(sanitize($request->input('id')))) {
            $delete = contact::where('id', "=", sanitize($request->input('id')))->delete();
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

}
