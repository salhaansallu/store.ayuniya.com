<?php
namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function getLeastAvailableStocks()
    {
        $leastAvailableStocks = Stock::orderBy('qty')->take(10)
        ->select('pro_id')
        ->get();

        return response()->json($leastAvailableStocks);
    }
}
