@extends('layouts.app')

@section('title', 'পণ্য পরিচালনা')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">পণ্য পরিচালনা</h1>
        @php
            $createRoute = auth()->user()->isOwner() ? route('owner.products.create') : route('manager.products.create');
        @endphp
        <a href="{{ $createRoute }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            নতুন পণ্য যোগ করুন
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">পণ্য কোড</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">নাম</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ক্রয়মূল্য</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">বিক্রয়মূল্য</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">বর্তমান স্টক</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($products as $product)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->sku }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">৳{{ number_format($product->purchase_price, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">৳{{ number_format($product->sell_price, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->current_stock }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @php
                            $editRoute = auth()->user()->isOwner() ? route('owner.products.edit', $product) : route('manager.products.edit', $product);
                            $deleteRoute = auth()->user()->isOwner() ? route('owner.products.destroy', $product) : route('manager.products.destroy', $product);
                        @endphp
                        <a href="{{ $editRoute }}" class="text-blue-600 hover:text-blue-900 mr-3">সম্পাদনা</a>
                        <form action="{{ $deleteRoute }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('আপনি কি নিশ্চিত?')">মুছুন</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
