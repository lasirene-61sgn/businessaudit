@extends('admin.layouts.app')

@section('title', 'Modify Customer Account')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
    <div class="mb-6 border-b border-gray-100 pb-4">
        <h1 class="text-xl font-bold text-gray-900">Edit Customer Information</h1>
        <p class="text-sm text-gray-500 mt-1">Leave the password field blank if you don't want to change it.</p>
    </div>

    <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT') <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>
            <div>
                <label for="mobile_number" class="block text-sm font-semibold text-gray-700 mb-1">Mobile Number</label>
                <input type="text" name="mobile_number" id="mobile_number" value="{{ old('mobile_number', $customer->mobile_number) }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password (Optional)</label>
            <input type="password" name="password" id="password" placeholder="Leave empty to keep existing password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Current Profile Image</label>
            <div class="flex items-center gap-4 mb-2">
                @if($customer->profile_image)
                    <img src="{{ asset('storage/' . $customer->profile_image) }}" alt="Avatar" class="w-16 h-16 rounded-lg object-cover border">
                @else
                    <div class="w-16 h-16 rounded-lg bg-gray-100 text-gray-400 flex items-center justify-center border border-dashed text-xs">No Image</div>
                @endif
                <div>
                    <label for="profile_image" class="block text-xs font-semibold text-indigo-600 hover:text-indigo-800 cursor-pointer">Upload New Photo</label>
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" class="hidden">
                    <p class="text-xs text-gray-400 mt-1">Accepts PNG, JPG, JPEG up to 5MB.</p>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.customers.index') }}" class="px-4 py-2 border rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-lg shadow transition">Update Customer</button>
        </div>
    </form>
</div>
@endsection