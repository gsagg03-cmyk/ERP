<?php

namespace App\Http\Controllers\Salesman;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\ProfitRealization;

class SalesmanController extends Controller
{
    public function dashboard()
    {
        $mySales = Sale::where('user_id', auth()->id())->with('product')->latest()->take(10)->get();
        $todaySales = Sale::where('user_id', auth()->id())->whereDate('created_at', today())->sum('total_amount');
        $todayProfit = Sale::where('user_id', auth()->id())->whereDate('created_at', today())->sum('profit');
        $todayRealizedProfit = ProfitRealization::whereHas('sale', function($q) {
            $q->where('user_id', auth()->id());
        })->whereDate('payment_date', today())->sum('profit_amount');
        $totalSales = Sale::where('user_id', auth()->id())->count();

        return view('salesman.dashboard', compact('mySales', 'todaySales', 'todayProfit', 'todayRealizedProfit', 'totalSales'));
    }
}
