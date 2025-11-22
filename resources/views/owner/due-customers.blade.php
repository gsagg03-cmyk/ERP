@extends('layouts.app')

@section('title', 'বকেয়া গ্রাহক তালিকা')

@section('content')
<div class="min-h-screen w-full px-2 sm:px-4 lg:px-6">
    <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">বকেয়া কাস্টমার তালিকা</h1>
        <a href="{{ route('owner.dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm sm:text-base">
            ড্যাশবোর্ডে ফিরুন
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="mb-4 sm:mb-6 bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-4 sm:p-6 text-white">
        <div class="text-xs sm:text-sm opacity-90">মোট বকেয়া</div>
        <div class="text-3xl sm:text-4xl font-bold">৳{{ number_format($totalDue, 2) }}</div>
        <div class="text-xs sm:text-sm mt-2">মোট {{ $dueCustomers->count() }} জন গ্রাহকের বকেয়া রয়েছে</div>
    </div>

    <!-- Search Box -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6 mb-4 sm:mb-6">
        <form method="GET" action="{{ route('owner.due-customers') }}" class="flex gap-3">
            <div class="flex-1">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="ফোন নম্বর, ভাউচার নম্বর বা কাস্টমার নাম দিয়ে খুঁজুন..."
                       class="shadow border rounded w-full py-2 px-3 text-sm sm:text-base text-gray-700">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 sm:px-6 rounded text-sm sm:text-base whitespace-nowrap">
                খুঁজুন
            </button>
            @if(request('search'))
            <a href="{{ route('owner.due-customers') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm sm:text-base">
                রিসেট
            </a>
            @endif
        </form>
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
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">স্ট্যাটাস</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ভাউচার</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">একশন</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($dueCustomers as $sale)
                    <tr class="{{ $sale->expected_clear_date && $sale->expected_clear_date->isPast() ? 'bg-red-50' : 'hover:bg-gray-50' }}">
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
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm hidden lg:table-cell">
                            @if($sale->payment_status == 'paid')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">পরিশোধিত</span>
                            @elseif($sale->payment_status == 'partial')
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">আংশিক</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">অপরিশোধিত</span>
                            @endif
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                            <div class="flex flex-col gap-1">
                                <a href="{{ route('owner.voucher.print', $sale->id) }}" target="_blank" class="text-blue-600 hover:text-blue-900 font-mono">
                                    {{ $sale->voucher_number ?? 'N/A' }}
                                </a>
                                @if($sale->profitRealizations->count() > 0)
                                    <div class="text-xs text-gray-500">
                                        @foreach($sale->profitRealizations as $pr)
                                            <a href="{{ route('owner.payment.voucher', $pr->id) }}" target="_blank" class="text-green-600 hover:text-green-900 block">
                                                {{ $pr->payment_voucher_number }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                            <a href="{{ route('owner.payment.record', $sale->id) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs">
                                টাকা নিন
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                            কোন বকেয়া নেই
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
