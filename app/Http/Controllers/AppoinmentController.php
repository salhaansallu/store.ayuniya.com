<?php

namespace App\Http\Controllers;

use App\Models\user_appointments;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppoinmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        redirectCookie();
        $appoinment = user_appointments::where("user_id", "=", "")->where("status", "=", "")->where("app_date", ">", date("Y-m-d h:i:s"))->whereBetween("app_date", [date("Y-m-d H:i:s"), date("Y-m-d H:i:s", strtotime('1 day'))])->get();
        return view('appointment')->with(['title' => 'Book an appointment | ' . config('app.name'), 'css' => 'appointment.scss', 'appointment' => $appoinment]);
    }

    public function admin()
    {
        if (isAdmin()) {
            if (isset($_GET['new']) && sanitize($_GET['new']) == "true") {
                $apps = DB::select('select * from user_appointments, users where user_id=users.id and user_id is not null and user_appointments.app_date > "' . date('Y-m-d') . '" and status = "" order by status ASC');
                return view('dashboard.views.appointment')->with(['css' => 'appointment.scss', 'apps' => $apps]);
            } else {
                $apps = DB::select('select * from user_appointments, users where user_id=users.id and user_id is not null and user_appointments.app_date > "' . date('Y-m-d') . '" order by status ASC');
                return view('dashboard.views.appointment')->with(['css' => 'appointment.scss', 'apps' => $apps]);
            }
        } else {
            return redirect(route('login'));
        }
    }

    public function book(Request $request)
    {
        if ($request->input('action') == "book" && sanitize($request->input('id'))) {
            user_appointments::where("app_id", "=", sanitize($request->input('id')))->update(["status" => "booked"]);
            return response(json_encode(array("error" => 0, "msg" => "Appointment placed successfully")));
        } elseif ($request->input('action') == "decline" && sanitize($request->input('id'))) {
            user_appointments::where("app_id", "=", sanitize($request->input('id')))->delete();
            return response(json_encode(array("error" => 0, "msg" => "Appointment deleted successfully")));
        } else {
            return response(json_encode(array("error" => 1, "msg" => "Sorry something went wrong")));
        }
    }

    public function bookAppointment(Request $request, $date)
    {
        if (Auth::check()) {
            if (DateTime::createFromFormat('d-m-Y', sanitize($date)) !== false) {
                $app_ver = user_appointments::where("user_id", "=", Auth::user()->id)->where("app_date", "=", date("Y-m-d", strtotime(sanitize($date)))." 00:00:00")->where("disease", "=", $request->input("des"));
                if ($app_ver->count() == 0) {
                    $app = new user_appointments();
                    $app->app_date = sanitize(date("Y-m-d", strtotime($date)));
                    $app->user_id = Auth::user()->id;
                    $app->disease = $request->input("des");
                    $app->status = "";
                    if ($app->save()) {
                        return response(json_encode(array("error" => 0, "msg" => "Appintment booked succesfully")));
                    } else {
                        return response(json_encode(array("error" => 1, "msg" => "Sorry something went wrong")));
                    }
                }
                else {
                    return response(json_encode(array("error" => 2, "msg" => "You have already booked for this date")));
                }
            } else {
                return response(json_encode(array("error" => 1, "msg" => "Sorry something went wrong")));
            }
        } else {
            return response(json_encode(array("error" => 3, "msg" => "login")));
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
     * @param  \App\Models\appoinment  $appoinment
     * @return \Illuminate\Http\Response
     */
    public function show(user_appointments $appoinment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\appoinment  $appoinment
     * @return \Illuminate\Http\Response
     */
    public function edit(user_appointments $appoinment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\appoinment  $appoinment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, user_appointments $appoinment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\appoinment  $appoinment
     * @return \Illuminate\Http\Response
     */
    public function destroy(user_appointments $appoinment)
    {
        //
    }
}
