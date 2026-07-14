@extends('admin.layouts.app')

@section('title', 'Customer Directory Management')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    
    <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Registered Customers</h1>
            <p class="text-sm text-gray-500 mt-1">Review profiles, mobile verification milestones, or update customer access attributes.</p>
        </div>
        <a href="{{ route('admin.customers.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-lg text-sm shadow transition duration-150">
            + Add New Customer
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs font-bold uppercase tracking-wider border-b border-gray-200">
                    <th class="px-6 py-4 w-20">Avatar</th>
                    <th class="px-6 py-4">Full Name</th>
                    <th class="px-6 py-4">Email Address</th>
                    <th class="px-6 py-4">Mobile Number</th>
                    <th class="px-6 py-4">Verification Status</th>
                    <th class="px-6 py-4 text-right">Control Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50/60 transition duration-100">
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($customer->profile_image)
                                <img src="{{ asset('storage/' . $customer->profile_image) }}" alt="Customer Profile Photo" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                            @else
                                <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-700 font-bold flex items-center justify-center border border-indigo-200 text-xs">
                                    {{ strtoupper(substr($customer->name, 0, 2)) }}
                                </div>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 font-semibold text-gray-900 whitespace-nowrap">
                            {{ $customer->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $customer->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-mono text-gray-600">
                            {{ $customer->mobile_number }}
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($customer->mobile_verified_at)
                                <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 border border-green-200 font-semibold px-2.5 py-1 rounded-full text-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Verified
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200 font-semibold px-2.5 py-1 rounded-full text-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Pending Otp
                                </span>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-right space-x-3 text-xs">
                            <a href="{{ route('admin.customers.edit', $customer->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold tracking-wide uppercase">
                                Edit
                            </a>
                            
                            <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you completely sure you want to permanently delete this customer profile?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold tracking-wide uppercase bg-transparent border-0 cursor-pointer p-0">
                                    Delete
                                </button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 font-medium">
                            No customers registered yet. Click <a href="{{ route('admin.customers.create') }}" class="text-indigo-600 underline">Add New Customer</a> to begin.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection