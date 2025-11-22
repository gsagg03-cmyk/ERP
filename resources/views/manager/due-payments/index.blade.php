@extends('layouts.app')

@section('title', 'বকেয়া পেমেন্ট পরিচালনা')

@section('content')
<div class="min-h-screen w-full px-2 sm:px-4 lg:px-6">
    <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">বকেয়া পেমেন্ট পরিচালনা</h1>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">কাস্টমার</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">ফোন</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">পণ্য</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">তারিখ</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">বকেয়া</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">পরিশোধের তারিখ</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">কাজ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($dueSales as $sale)
                    <tr class="{{ $sale->expected_clear_date && $sale->expected_clear_date->isPast() ? 'bg-red-50' : '' }}">
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-gray-900">
                            {{ $sale->customer_name ?? 'N/A' }}
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden sm:table-cell">
                            {{ $sale->customer_phone ?? 'N/A' }}
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                            {{ $sale->product->name }}
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden md:table-cell">
                            {{ $sale->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-red-600 font-bold">
                            ৳{{ number_format($sale->due_amount, 2) }}
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden lg:table-cell">
                            @if($sale->expected_clear_date)
                                {{ $sale->expected_clear_date->format('d/m/Y') }}
                                @if($sale->expected_clear_date->isPast())
                                    <br><span class="text-red-600 text-xs font-semibold">(মেয়াদোত্তীর্ণ)</span>
                                @endif
                            @else
                                <span class="text-gray-400">নির্ধারিত নয়</span>
                            @endif
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium">
                            <button onclick="openPaymentModal({{ $sale->id }}, '{{ $sale->customer_name }}', {{ $sale->due_amount }})" 
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 sm:py-2 px-2 sm:px-3 rounded text-xs sm:text-sm">
                                পেমেন্ট
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            কোন বকেয়া পেমেন্ট নেই
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 sm:w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">পেমেন্ট করুন</h3>
            <form id="paymentForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">কাস্টমার নাম</label>
                    <p id="customerName" class="text-gray-600"></p>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">বকেয়া টাকা</label>
                    <p id="dueAmount" class="text-red-600 font-bold text-lg"></p>
                </div>
                <div class="mb-4">
                    <label for="payment_amount" class="block text-gray-700 text-sm font-bold mb-2">পরিশোধের পরিমাণ</label>
                    <input type="number" 
                           name="payment_amount" 
                           id="payment_amount" 
                           step="0.01" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           required>
                </div>
                <div class="flex flex-col sm:flex-row justify-end gap-2">
                    <button type="button" 
                            onclick="closePaymentModal()" 
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        বাতিল
                    </button>
                    <button type="submit" 
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        পেমেন্ট করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openPaymentModal(saleId, customerName, dueAmount) {
    document.getElementById('paymentModal').classList.remove('hidden');
    document.getElementById('customerName').textContent = customerName || 'N/A';
    document.getElementById('dueAmount').textContent = '৳' + parseFloat(dueAmount).toFixed(2);
    document.getElementById('payment_amount').setAttribute('max', dueAmount);
    document.getElementById('paymentForm').action = '/manager/due-payments/' + saleId;
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
    document.getElementById('paymentForm').reset();
}
</script>
@endsection
