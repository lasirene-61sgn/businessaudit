@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">
    
    <div class="bg-gradient-to-r from-indigo-800 to-indigo-600 rounded-2xl shadow-md p-6 md:p-8 text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Welcome Back, {{ auth()->guard('admin')->user()->name }}!</h1>
            <p class="text-indigo-100 mt-1 text-sm md:text-base">Here is what's happening with your control panel ecosystem today.</p>
        </div>
        <div class="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-xl text-xs md:text-sm font-medium border border-white/10">
            System Date: {{ now()->format('F d, Y') }}
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Customers</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">
                    {{ \App\Models\Customer::count() }}
                </h3>
            </div>
            <div class="w-12 h-12 bg-indigo-50 rounded-xl text-indigo-600 flex items-center justify-center font-bold text-xl border border-indigo-100">
                👥
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Verified Numbers</p>
                <h3 class="text-3xl font-bold text-green-600 mt-2">
                    {{ \App\Models\Customer::whereNotNull('mobile_verified_at')->count() }}
                </h3>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-xl text-green-600 flex items-center justify-center font-bold text-xl border border-green-100">
                ✓
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pending Verification</p>
                <h3 class="text-3xl font-bold text-amber-600 mt-2">
                    {{ \App\Models\Customer::whereNull('mobile_verified_at')->count() }}
                </h3>
            </div>
            <div class="w-12 h-12 bg-amber-50 rounded-xl text-amber-600 flex items-center justify-center font-bold text-xl border border-amber-100">
                ⏳
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-8">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Quick Management Utilities</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            
            <a href="{{ route('admin.customers.index') }}" class="group block p-5 border border-gray-200 hover:border-indigo-500 rounded-xl hover:bg-indigo-50/20 transition duration-150">
                <div class="flex items-center gap-3">
                    <span class="text-xl group-hover:scale-110 transition duration-150">📋</span>
                    <span class="font-semibold text-gray-900 group-hover:text-indigo-600 transition">Customer Management Directory</span>
                </div>
                <p class="text-sm text-gray-500 mt-2 pl-8">Browse directory entries, review authentication states, or safely remove accounts.</p>
            </a>

            <a href="{{ route('admin.customers.create') }}" class="group block p-5 border border-gray-200 hover:border-indigo-500 rounded-xl hover:bg-indigo-50/20 transition duration-150">
                <div class="flex items-center gap-3">
                    <span class="text-xl group-hover:scale-110 transition duration-150">➕</span>
                    <span class="font-semibold text-gray-900 group-hover:text-indigo-600 transition">Create New Customer Profile</span>
                </div>
                <p class="text-sm text-gray-500 mt-2 pl-8">Register a fresh user record into the custom table database with validation filters.</p>
            </a>

        </div>
    </div>

</div>
@endsection