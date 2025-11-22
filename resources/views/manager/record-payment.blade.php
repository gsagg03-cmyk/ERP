@extends('layouts.app')

@section('title', 'পেমেন্ট রেকর্ড করুন')

@section('content')
<div class="min-h-screen w-full px-2 sm:px-4 lg:px-6">
    <div class="max-w-3xl mx-auto">
        <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">পেমেন্ট রেকর্ড করুন</h1>
            <a href="{{ route('manager.due-customers') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm sm:text-base">
                বকেয়া তালিকায় ফিরুন
            </a>
        </div>

        <!-- Sale Details Card -->
        <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 mb-6">
            <h2 class="text-lg font-bold mb-4 text-gray-800">বিক্রয় বিবরণ</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600">ভাউচার নম্বর</label>
                    <p class="text-lg font-mono text-gray-900">{{ $sale->voucher_number }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">তারিখ</label>
                    <p class="text-lg text-gray-900">{{ $sale->created_at->format('d/m/Y') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">কাস্টমার নাম</label>
                    <p class="text-lg text-gray-900">{{ $sale->customer_name ?? 'N/A' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">ফোন নম্বর</label>
                    <p class="text-lg text-gray-900">{{ $sale->customer_phone ?? 'N/A' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">পণ্য</label>
                    <p class="text-lg text-gray-900">{{ $sale->product->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">পরিমাণ</label>
                    <p class="text-lg text-gray-900">{{ $sale->quantity }}</p>
                </div>
            </div>

            <div class="border-t pt-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-blue-50 p-4 rounded">
                    <label class="block text-sm font-medium text-blue-600">মোট টাকা</label>
                    <p class="text-2xl font-bold text-blue-900">৳{{ number_format($sale->total_amount, 2) }}</p>
                </div>
                
                <div class="bg-green-50 p-4 rounded">
                    <label class="block text-sm font-medium text-green-600">পরিশোধিত</label>
                    <p class="text-2xl font-bold text-green-900">৳{{ number_format($sale->paid_amount, 2) }}</p>
                </div>
                
                <div class="bg-red-50 p-4 rounded">
                    <label class="block text-sm font-medium text-red-600">বকেয়া</label>
                    <p class="text-2xl font-bold text-red-900">৳{{ number_format($sale->due_amount, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6">
            <h2 class="text-lg font-bold mb-4 text-gray-800">পেমেন্ট তথ্য</h2>
            
            <form action="{{ route('manager.payment.store', $sale->id) }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="payment_amount" class="block text-sm font-bold text-gray-700 mb-2">
                        পেমেন্ট পরিমাণ (সর্বোচ্চ: ৳{{ number_format($sale->due_amount, 2) }})
                    </label>
                    <input type="number" 
                           name="payment_amount" 
                           id="payment_amount" 
                           step="0.01" 
                           min="0.01" 
                           max="{{ $sale->due_amount }}"
                           value="{{ old('payment_amount', $sale->due_amount) }}"
                           class="shadow border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-lg @error('payment_amount') border-red-500 @enderror"
                           required>
                    @error('payment_amount')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                    
                    <!-- Quick Amount Buttons -->
                    <div class="mt-3 flex flex-wrap gap-2">
                        <button type="button" onclick="setAmount({{ $sale->due_amount }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                            সম্পূর্ণ বকেয়া
                        </button>
                        @if($sale->due_amount >= 1000)
                        <button type="button" onclick="setAmount(1000)" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded text-sm">
                            ৳১,০০০
                        </button>
                        @endif
                        @if($sale->due_amount >= 5000)
                        <button type="button" onclick="setAmount(5000)" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded text-sm">
                            ৳৫,০০০
                        </button>
                        @endif
                        @if($sale->due_amount >= 10000)
                        <button type="button" onclick="setAmount(10000)" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded text-sm">
                            ৳১০,০০০
                        </button>
                        @endif
                    </div>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                এই পেমেন্ট রেকর্ড করার পরে, বিক্রয়ের বকেয়া আপডেট হবে এবং নগদ লাভ হিসাবে অন্তর্ভুক্ত হবে।
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="flex-1 bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded text-lg">
                        পেমেন্ট রেকর্ড করুন
                    </button>
                    <a href="{{ route('manager.due-customers') }}" class="flex-1 text-center bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded text-lg">
                        বাতিল করুন
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setAmount(amount) {
    document.getElementById('payment_amount').value = amount;
}
</script>
@endsection
