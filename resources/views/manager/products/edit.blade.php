@extends('layouts.app')

@section('title', 'পণ্য সম্পাদনা')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">পণ্য সম্পাদনা</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        @php
            $updateRoute = auth()->user()->isOwner() ? route('owner.products.update', $product) : route('manager.products.update', $product);
            $indexRoute = auth()->user()->isOwner() ? route('owner.products.index') : route('manager.products.index');
        @endphp
        <form method="POST" action="{{ $updateRoute }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">পণ্যের নাম</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="sku" class="block text-gray-700 text-sm font-bold mb-2">পণ্য কোড</label>
                <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('sku') border-red-500 @enderror" required>
                @error('sku')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="purchase_price" class="block text-gray-700 text-sm font-bold mb-2">ক্রয়মূল্য (৳)</label>
                <input type="number" step="0.01" name="purchase_price" id="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('purchase_price') border-red-500 @enderror" required>
                @error('purchase_price')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="sell_price" class="block text-gray-700 text-sm font-bold mb-2">বিক্রয়মূল্য (৳)</label>
                <input type="number" step="0.01" name="sell_price" id="sell_price" value="{{ old('sell_price', $product->sell_price) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('sell_price') border-red-500 @enderror" required>
                @error('sell_price')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ $indexRoute }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    বাতিল
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    পণ্য আপডেট করুন
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
