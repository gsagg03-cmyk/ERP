<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sale;
use App\Models\Product;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalOwners = User::role('owner')->count();
        $totalSales = Sale::sum('total_amount');
        $totalProfit = Sale::sum('profit');
        $recentSales = Sale::with(['product', 'user'])->latest()->take(10)->get();

        return view('superadmin.dashboard', compact('totalUsers', 'totalOwners', 'totalSales', 'totalProfit', 'recentSales'));
    }
}
