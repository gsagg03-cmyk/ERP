@extends('layouts.guest')

@section('title', 'লগইন - ইআরপি সিস্টেম')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">ইআরপি সিস্টেমে লগইন করুন</h2>
        
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">ফোন নম্বর</label>
                <input type="text" 
                       name="phone" 
                       id="phone" 
                       value="{{ old('phone') }}"
                       placeholder="01711111111"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('phone') border-red-500 @enderror" 
                       required 
                       autofocus>
                @error('phone')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">পাসওয়ার্ড</label>
                <input type="password" 
                       name="password" 
                       id="password" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror" 
                       required>
                @error('password')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="form-checkbox h-4 w-4 text-blue-600">
                    <span class="ml-2 text-sm text-gray-700">আমাকে মনে রাখুন</span>
                </label>
            </div>

            <div class="flex items-center justify-center">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                    লগইন
                </button>
            </div>
        </form>

        <div class="mt-8">
            <p class="font-semibold text-center mb-3 text-gray-700">ডেমো অ্যাকাউন্ট (ক্লিক করুন)</p>
            <div class="grid grid-cols-1 gap-2">
                <button onclick="fillLogin('1234567890', 'password')" class="bg-purple-100 hover:bg-purple-200 text-purple-800 font-semibold py-2 px-4 rounded text-sm transition">
                    🔐 Super Admin
                </button>
                <button onclick="fillLogin('01711111111', 'password')" class="bg-green-100 hover:bg-green-200 text-green-800 font-semibold py-2 px-4 rounded text-sm transition">
                    👨‍💼 Owner (রহিম সাহেব)
                </button>
                <button onclick="fillLogin('01722222222', 'password')" class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-semibold py-2 px-4 rounded text-sm transition">
                    📊 Manager (করিম ম্যানেজার)
                </button>
                <button onclick="fillLogin('01733333333', 'password')" class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 font-semibold py-2 px-4 rounded text-sm transition">
                    🛍️ Salesman 1 (আলী সেলসম্যান)
                </button>
                <button onclick="fillLogin('01744444444', 'password')" class="bg-orange-100 hover:bg-orange-200 text-orange-800 font-semibold py-2 px-4 rounded text-sm transition">
                    🛍️ Salesman 2 (হাসান সেলসম্যান)
                </button>
            </div>
        </div>

        <div class="mt-6 text-center text-xs text-gray-500">
            <p>All passwords: <strong>password</strong></p>
        </div>
    </div>
</div>

<script>
function fillLogin(phone, password) {
    document.getElementById('phone').value = phone;
    document.getElementById('password').value = password;
    // Optional: Auto-submit after filling
    // document.getElementById('loginForm').submit();
}
</script>
@endsection
