<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sale;
use App\Models\Product;
use App\Models\ProfitRealization;
use Carbon\Carbon;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $totalSalesmen = User::role('salesman')->where('created_by', auth()->id())->count();
        $totalProducts = Product::count();
        
        // Today's data
        $todaySales = Sale::whereDate('created_at', Carbon::today())->sum('total_amount');
        $todayProfit = Sale::whereDate('created_at', Carbon::today())->sum('profit');
        $todayRealizedProfit = ProfitRealization::whereDate('payment_date', Carbon::today())->sum('profit_amount');
        
        // This month's data
        $monthSales = Sale::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_amount');
        $monthProfit = Sale::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('profit');
        $monthRealizedProfit = ProfitRealization::whereYear('payment_date', Carbon::now()->year)
            ->whereMonth('payment_date', Carbon::now()->month)
            ->sum('profit_amount');
        
        // Customer dues
        $totalDue = Sale::where('due_amount', '>', 0)->sum('due_amount');
        $dueCustomers = Sale::where('due_amount', '>', 0)
            ->with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Recent sales
        $recentSales = Sale::with(['product', 'user'])->latest()->take(10)->get();

        return view('manager.dashboard', compact(
            'totalSalesmen', 
            'totalProducts',
            'todaySales', 
            'todayProfit',
            'todayRealizedProfit',
            'monthSales',
            'monthProfit',
            'monthRealizedProfit',
            'totalDue', 
            'dueCustomers',
            'recentSales'
        ));
    }
    
    public function dueCustomers()
    {
        $query = Sale::where('due_amount', '>', 0)
            ->with(['product', 'user', 'profitRealizations']);
        
        // Search by phone or voucher
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('customer_phone', 'LIKE', '%' . $search . '%')
                  ->orWhere('voucher_number', 'LIKE', '%' . $search . '%')
                  ->orWhere('customer_name', 'LIKE', '%' . $search . '%');
            });
        }
        
        $dueCustomers = $query->orderBy('created_at', 'desc')->get();
        $totalDue = $query->sum('due_amount');
        
        return view('manager.due-customers', compact('dueCustomers', 'totalDue'));
    }
    
    public function recordPayment($saleId)
    {
        $sale = Sale::findOrFail($saleId);
        
        return view('manager.record-payment', compact('sale'));
    }
    
    public function storePayment($saleId)
    {
        $sale = Sale::findOrFail($saleId);
        
        $validated = request()->validate([
            'payment_amount' => 'required|numeric|min:0.01|max:' . $sale->due_amount,
        ]);
        
        // Generate payment voucher number
        $date = now()->format('Ymd');
        $lastPaymentVoucher = ProfitRealization::whereDate('created_at', today())
            ->whereNotNull('payment_voucher_number')
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastPaymentVoucher && $lastPaymentVoucher->payment_voucher_number) {
            $lastNumber = (int) substr($lastPaymentVoucher->payment_voucher_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        $paymentVoucherNumber = 'PV-' . $date . '-' . $newNumber;
        
        // Update sale payment
        $sale->paid_amount += $validated['payment_amount'];
        $sale->save();
        
        // Calculate proportional profit for this payment
        $profitRatio = $sale->profit / $sale->total_amount;
        $profitAmount = $validated['payment_amount'] * $profitRatio;
        
        // Record profit realization
        $profitRealization = ProfitRealization::create([
            'sale_id' => $sale->id,
            'payment_amount' => $validated['payment_amount'],
            'payment_voucher_number' => $paymentVoucherNumber,
            'profit_amount' => $profitAmount,
            'payment_date' => now(),
            'recorded_by' => auth()->id(),
        ]);
        
        return redirect()->route('manager.payment.voucher', $profitRealization->id)
            ->with('success', 'পেমেন্ট সফলভাবে রেকর্ড করা হয়েছে!');
    }
    
    public function paymentVoucher($profitRealizationId)
    {
        $profitRealization = ProfitRealization::with(['sale.product', 'sale.user'])->findOrFail($profitRealizationId);
        $sale = $profitRealization->sale;
        
        // Get owner
        $salesman = $sale->user;
        $manager = $salesman->hasRole('manager') ? $salesman : $salesman->creator;
        $owner = $manager->hasRole('owner') ? $manager : $manager->creator;
        
        // Get voucher template
        $template = \App\Models\VoucherTemplate::where('owner_id', $owner->id)->first();
        
        return view('voucher.payment-voucher', compact('profitRealization', 'sale', 'template'));
    }
}
