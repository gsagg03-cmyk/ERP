<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\VoucherTemplate;
use App\Models\ProfitRealization;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function print($saleId)
    {
        $sale = Sale::with(['product', 'user'])->findOrFail($saleId);
        
        // Get owner of this sale
        $salesman = $sale->user;
        $manager = $salesman->hasRole('manager') ? $salesman : $salesman->creator;
        $owner = $manager->hasRole('owner') ? $manager : $manager->creator;
        
        // Get voucher template for the owner
        $template = VoucherTemplate::where('owner_id', $owner->id)->first();
        
        return view('voucher.print', compact('sale', 'template'));
    }

    public function paymentVoucher($profitRealizationId)
    {
        $profitRealization = ProfitRealization::with(['sale.product', 'sale.user', 'recordedBy'])
            ->findOrFail($profitRealizationId);
        
        $sale = $profitRealization->sale;
        
        // Get owner of this sale
        $salesman = $sale->user;
        $manager = $salesman->hasRole('manager') ? $salesman : $salesman->creator;
        $owner = $manager->hasRole('owner') ? $manager : $manager->creator;
        
        // Get voucher template for the owner
        $template = VoucherTemplate::where('owner_id', $owner->id)->first();
        
        return view('voucher.payment-voucher', compact('profitRealization', 'sale', 'template'));
    }
}
