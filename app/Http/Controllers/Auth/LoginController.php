<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function ApiLogin(Request $request)
    {
        $user = User::where("email", "=", $request->input("email"))->get();
        if ($user->count() > 0) {
            if (Hash::check($request->input("password"), $user[0]->password)) {
                echo json_encode(array("error"=>"0", "message"=>"Login successful"));
            }
            else {
                echo json_encode(array("error"=>"1", "message"=>"Invalid Credencials"));
            }
        }
    }
}
