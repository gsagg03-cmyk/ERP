@extends('layouts.app')

@section('title', 'খরচ ব্যবস্থাপনা')

@section('content')
<div class="min-h-screen w-full px-2 sm:px-4 lg:px-6">
    <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">খরচ ব্যবস্থাপনা</h1>
        <a href="{{ route('owner.expenses.create') }}" class="w-full sm:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 sm:px-6 rounded text-sm sm:text-base text-center">
            + নতুন খরচ যোগ করুন
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6 mb-4 sm:mb-6">
        <form method="GET" action="{{ route('owner.expenses.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-700 text-xs sm:text-sm font-bold mb-2">শুরুর তারিখ</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="shadow border rounded w-full py-2 px-3 text-sm sm:text-base text-gray-700">
                </div>
                <div>
                    <label class="block text-gray-700 text-xs sm:text-sm font-bold mb-2">শেষ তারিখ</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="shadow border rounded w-full py-2 px-3 text-sm sm:text-base text-gray-700">
                </div>
                <div>
                    <label class="block text-gray-700 text-xs sm:text-sm font-bold mb-2">শ্রেণী</label>
                    <select name="category" class="shadow border rounded w-full py-2 px-3 text-sm sm:text-base text-gray-700">
                        <option value="">সব শ্রেণী</option>
                        @foreach($categories as $key => $value)
                            <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm sm:text-base">
                        খুঁজুন
                    </button>
                    <a href="{{ route('owner.expenses.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm sm:text-base">
                        রিসেট
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Total Summary -->
    <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-lg p-4 sm:p-6 mb-4 sm:mb-6 text-white">
        <div class="text-sm sm:text-base opacity-90">মোট খরচ</div>
        <div class="text-3xl sm:text-4xl font-bold">৳{{ number_format($totalExpense, 2) }}</div>
    </div>

    <!-- Expenses Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">তারিখ</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">শ্রেণী</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">বিবরণ</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">টাকা</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">নোট</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">কার্যক্রম</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($expenses as $expense)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">
                                {{ $expense->expense_date->format('d/m/Y') }}
                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $expense->category_name }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-6 py-4 text-xs sm:text-sm text-gray-900">
                                {{ $expense->description }}
                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm font-bold text-red-600">
                                ৳{{ number_format($expense->amount, 2) }}
                            </td>
                            <td class="px-3 sm:px-6 py-4 text-xs sm:text-sm text-gray-500">
                                {{ $expense->notes ?? '-' }}
                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm font-medium space-x-2">
                                <a href="{{ route('owner.expenses.edit', $expense) }}" class="text-indigo-600 hover:text-indigo-900">সম্পাদনা</a>
                                <form action="{{ route('owner.expenses.destroy', $expense) }}" method="POST" class="inline" onsubmit="return confirm('আপনি কি নিশ্চিত?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">মুছুন</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                কোন খরচ পাওয়া যায়নি
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($expenses->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
