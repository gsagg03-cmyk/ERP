@extends('layouts.app')

@section('title', 'নতুন খরচ যোগ করুন')

@section('content')
<div class="min-h-screen w-full px-2 sm:px-4 lg:px-6">
    <div class="mb-4 sm:mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">নতুন খরচ যোগ করুন</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-4 sm:p-6 lg:p-8 max-w-2xl">
        <form method="POST" action="{{ route('owner.expenses.store') }}">
            @csrf

            <div class="mb-4 sm:mb-6">
                <label for="category" class="block text-gray-700 text-xs sm:text-sm font-bold mb-2">শ্রেণী *</label>
                <select name="category" id="category" class="shadow border rounded w-full py-2 px-3 text-sm sm:text-base text-gray-700 @error('category') border-red-500 @enderror" required>
                    <option value="">শ্রেণী নির্বাচন করুন</option>
                    @foreach($categories as $key => $value)
                        <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
                @error('category')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4 sm:mb-6">
                <label for="description" class="block text-gray-700 text-xs sm:text-sm font-bold mb-2">বিবরণ *</label>
                <input type="text" 
                       name="description" 
                       id="description" 
                       value="{{ old('description') }}" 
                       class="shadow border rounded w-full py-2 px-3 text-sm sm:text-base text-gray-700 @error('description') border-red-500 @enderror" 
                       placeholder="যেমন: অফিসের ভাড়া - নভেম্বর ২০২৫"
                       required>
                @error('description')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
                <div>
                    <label for="amount" class="block text-gray-700 text-xs sm:text-sm font-bold mb-2">টাকার পরিমাণ (৳) *</label>
                    <input type="number" 
                           name="amount" 
                           id="amount" 
                           value="{{ old('amount') }}" 
                           step="0.01"
                           min="0"
                           class="shadow border rounded w-full py-2 px-3 text-sm sm:text-base text-gray-700 @error('amount') border-red-500 @enderror" 
                           required>
                    @error('amount')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="expense_date" class="block text-gray-700 text-xs sm:text-sm font-bold mb-2">তারিখ *</label>
                    <input type="date" 
                           name="expense_date" 
                           id="expense_date" 
                           value="{{ old('expense_date', date('Y-m-d')) }}" 
                           class="shadow border rounded w-full py-2 px-3 text-sm sm:text-base text-gray-700 @error('expense_date') border-red-500 @enderror" 
                           required>
                    @error('expense_date')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="notes" class="block text-gray-700 text-xs sm:text-sm font-bold mb-2">নোট (ঐচ্ছিক)</label>
                <textarea name="notes" 
                          id="notes" 
                          rows="3"
                          class="shadow border rounded w-full py-2 px-3 text-sm sm:text-base text-gray-700 @error('notes') border-red-500 @enderror"
                          placeholder="অতিরিক্ত তথ্য...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 items-center sm:justify-between">
                <a href="{{ route('owner.expenses.index') }}" class="w-full sm:w-auto bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 sm:px-6 rounded text-sm sm:text-base text-center">
                    বাতিল
                </a>
                <button type="submit" class="w-full sm:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 sm:px-6 rounded text-sm sm:text-base">
                    খরচ যোগ করুন
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
