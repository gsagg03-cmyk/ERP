@extends('layouts.app')

@section('title', 'সকল বিক্রয় তালিকা')

@section('content')
<div class="min-h-screen w-full px-2 sm:px-4 lg:px-6">
    <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">সকল বিক্রয় তালিকা</h1>
        <a href="{{ route('owner.dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm sm:text-base">
            ড্যাশবোর্ডে ফিরুন
        </a>
    </div>

    <!-- Date Filter Form -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6 mb-6">
        <form method="GET" action="{{ route('owner.all-sales') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">শুরুর তারিখ</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" 
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-base p-2">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">শেষের তারিখ</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" 
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-base p-2">
            </div>
            <div>
                <label for="voucher_search" class="block text-sm font-medium text-gray-700 mb-2">ভাউচার নম্বর</label>
                <input type="text" name="voucher_search" id="voucher_search" value="{{ request('voucher_search') }}" 
                       placeholder="V-20251122-0001"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-base p-2">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 sm:px-6 rounded text-sm sm:text-base">
                    খুঁজুন
                </button>
                <a href="{{ route('owner.all-sales') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm sm:text-base">
                    রিসেট
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-4 sm:mb-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-3 sm:p-6 text-white">
            <div class="text-xs sm:text-sm opacity-90">মোট বিক্রয়</div>
            <div class="text-xl sm:text-3xl font-bold">৳{{ number_format($totalSales, 2) }}</div>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-3 sm:p-6 text-white">
            <div class="text-xs sm:text-sm opacity-90">মোট লাভ</div>
            <div class="text-xl sm:text-3xl font-bold">৳{{ number_format($totalProfit, 2) }}</div>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-3 sm:p-6 text-white">
            <div class="text-xs sm:text-sm opacity-90">মোট পরিশোধিত</div>
            <div class="text-xl sm:text-3xl font-bold">৳{{ number_format($totalPaid, 2) }}</div>
        </div>
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-3 sm:p-6 text-white">
            <div class="text-xs sm:text-sm opacity-90">মোট বকেয়া</div>
            <div class="text-xl sm:text-3xl font-bold">৳{{ number_format($totalDue, 2) }}</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">তারিখ</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ভাউচার</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">পণ্য</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">কাস্টমার</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">ফোন</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">পরিমাণ</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">মোট</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">পরিশোধিত</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">বকেয়া</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">লাভ</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">সেলসম্যান</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                            {{ $sale->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                            <a href="{{ route('voucher.print', $sale->id) }}" target="_blank" 
                               class="font-mono text-blue-600 hover:text-blue-800 hover:underline font-semibold">
                                🧾 {{ $sale->voucher_number ?? 'N/A' }}
                            </a>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-gray-900">
                            {{ $sale->product->name }}
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden sm:table-cell">
                            {{ $sale->customer_name ?? '-' }}
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-600 hidden sm:table-cell">
                            {{ $sale->customer_phone ?? '-' }}
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">
                            {{ $sale->quantity }}
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-semibold text-gray-900">
                            ৳{{ number_format($sale->total_amount, 2) }}
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-green-600 font-semibold hidden md:table-cell">
                            ৳{{ number_format($sale->paid_amount, 2) }}
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-bold hidden md:table-cell">
                            <span class="{{ $sale->due_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                ৳{{ number_format($sale->due_amount, 2) }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-blue-600 font-semibold hidden lg:table-cell">
                            ৳{{ number_format($sale->profit, 2) }}
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden lg:table-cell">
                            {{ $sale->user->name }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="px-6 py-8 text-center text-gray-500">
                            কোন বিক্রয় নেই
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-3 sm:px-6 py-4 bg-gray-50">
            {{ $sales->links() }}
        </div>
    </div>
</div>
@endsection
