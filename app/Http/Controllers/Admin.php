<?php

namespace App\Http\Controllers;

use App\Models\user_appointments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Admin extends Controller
{
    public function index()
    {
        if (isAdmin()) {
            $jan = getOrders(date("Y") . "-1-1", date("Y") . "-1-31", true);
            $feb = getOrders(date("Y") . "-2-1", date("Y") . "-2-28", true);
            $mar = getOrders(date("Y") . "-3-1", date("Y") . "-3-31", true);
            $apr = getOrders(date("Y") . "-4-1", date("Y") . "-4-30", true);
            $may = getOrders(date("Y") . "-5-1", date("Y") . "-5-31", true);
            $jun = getOrders(date("Y") . "-6-1", date("Y") . "-6-30", true);
            $jul = getOrders(date("Y") . "-7-1", date("Y") . "-7-31", true);
            $aug = getOrders(date("Y") . "-8-1", date("Y") . "-8-31", true);
            $sep = getOrders(date("Y") . "-9-1", date("Y") . "-9-30", true);
            $oct = getOrders(date("Y") . "-10-1", date("Y") . "-10-31", true);
            $nov = getOrders(date("Y") . "-11-1", date("Y") . "-11-30", true);
            $dec = getOrders(date("Y") . "-12-1", date("Y") . "-12-31", true);

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

            $low_stock = DB::select("select * from products, varients where products.id=varients.pro_id and qty <= 3");
            $apps = DB::select('select * from user_appointments, users where user_id=users.id and user_id is not null and user_appointments.app_date = "' . date('Y-m-d') . '" and status = "" order by status ASC');

            //dd($appointments);
            return view('dashboard.views.index')->with(['css' => 'index.scss', 'sales' => $sales, 'low_stock' => $low_stock, 'appointments' => $apps]);
        }
        else{
            return redirect(route('login'));
        }
    }
}
