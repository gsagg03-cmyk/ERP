<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\ProfitRealization;
use App\Models\Expense;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function superAdminReports(Request $request)
    {
        return $this->generateReports($request, 'superadmin.reports');
    }

    public function ownerReports(Request $request)
    {
        $dateFrom = $request->input('date_from', today()->toDateString());
        $dateTo = $request->input('date_to', today()->toDateString());

        $sales = Sale::with(['product', 'user'])
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->get();

        $totalSales = $sales->sum('total_amount');
        $totalProfit = $sales->sum('profit');
        $totalPaid = $sales->sum('paid_amount');
        $totalDue = $sales->sum('due_amount');
        $totalQuantity = $sales->sum('quantity');

        // Realized profit during this period (from ProfitRealization)
        $realizedProfit = ProfitRealization::whereBetween('payment_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->sum('profit_amount');

        // Due customers in the date range
        $dueCustomers = Sale::where('due_amount', '>', 0)
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->with(['product', 'user'])
            ->get();

        // Due collection during this period (payments received in this period)
        $dueCollection = ProfitRealization::whereBetween('payment_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->where('notes', '!=', 'Initial sale payment')
            ->sum('payment_amount');

        // Total expenses during this period
        $totalExpenses = Expense::whereBetween('expense_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->sum('amount');

        return view('owner.reports', compact(
            'sales', 
            'totalSales', 
            'totalProfit',
            'realizedProfit',
            'totalPaid',
            'totalDue',
            'totalQuantity',
            'totalExpenses',
            'dueCustomers',
            'dueCollection',
            'dateFrom', 
            'dateTo'
        ));
    }

    public function managerReports(Request $request)
    {
        $dateFrom = $request->input('date_from', today()->toDateString());
        $dateTo = $request->input('date_to', today()->toDateString());

        $sales = Sale::with(['product', 'user'])
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->get();

        $totalSales = $sales->sum('total_amount');
        $totalProfit = $sales->sum('profit');
        $totalPaid = $sales->sum('paid_amount');
        $totalDue = $sales->sum('due_amount');
        $totalQuantity = $sales->sum('quantity');

        // Realized profit during this period (from ProfitRealization)
        $realizedProfit = ProfitRealization::whereBetween('payment_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->sum('profit_amount');

        // Due customers in the date range
        $dueCustomers = Sale::where('due_amount', '>', 0)
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->with(['product', 'user'])
            ->get();

        // Due collection during this period (payments received in this period)
        $dueCollection = ProfitRealization::whereBetween('payment_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->where('notes', '!=', 'Initial sale payment')
            ->sum('payment_amount');

        return view('manager.reports', compact(
            'sales', 
            'totalSales', 
            'totalProfit',
            'realizedProfit',
            'totalPaid',
            'totalDue',
            'totalQuantity',
            'dueCustomers',
            'dueCollection',
            'dateFrom', 
            'dateTo'
        ));
    }

    private function generateReports(Request $request, string $view)
    {
        $dateFrom = $request->input('date_from', today()->toDateString());
        $dateTo = $request->input('date_to', today()->toDateString());

        $sales = Sale::with(['product', 'user'])
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->get();

        $totalSales = $sales->sum('total_amount');
        $totalProfit = $sales->sum('profit');
        $totalQuantity = $sales->sum('quantity');

        $products = Product::all();
        $totalStockValue = $products->sum(function($product) {
            return $product->getStockValue();
        });

        return view($view, compact('sales', 'totalSales', 'totalProfit', 'totalQuantity', 'totalStockValue', 'dateFrom', 'dateTo'));
    }
}
