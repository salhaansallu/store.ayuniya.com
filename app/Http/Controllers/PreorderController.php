<?php

namespace App\Http\Controllers;

use App\Models\preorder;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;;
class PreorderController extends Controller
{


    public function index()
    {
        if (isAdmin() || isOrderManager() || isCustomerCareManager()) {
            $preorder = preorder::get();
            return view("dashboard.views.preorders")->with(['css' => 'orders.scss', 'preorder' => $preorder]);
        } else {
            return redirect(route('login'));
        }
    }



    public function store(Request $request)
    {
        // Validate the request data here if needed

        // Get the values from the request
        $productID = $request->input('product_id');
        $productName = $request->input('product_name');
        $userID = auth()->id(); // Assuming you are using authentication
        $quantity = $request->input('quantity');

        // Store the values in the preorders table
        preorder::create([
            'product_id' => $productID,
            'user_id' => $userID,
            'product_name' => $productName,
            'Quantity' => $quantity,
        ]);

        // You can return a response if needed
        return response()->json(['message' => 'Preorder stored successfully']);
    }

    public function delete(Request $request)
    {
        $response = array();
        if (sanitize($request->input('action')) == "delete" && !empty(sanitize($request->input('id')))) {
            $delete = preorder::where('id', "=", sanitize($request->input('id')))->delete();
            if ($delete) {
                $response = array(
                    "error" => 0,
                    "msg" => "Preorder deleted"
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
