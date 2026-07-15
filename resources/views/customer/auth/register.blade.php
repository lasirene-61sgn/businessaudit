<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center py-10">
    <div class="bg-white p-8 rounded-xl shadow-sm border w-full max-w-lg mx-auto">
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('logo.png') }}" alt="La Sirene Logo" class="h-12 object-contain mb-4">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Create Your Account</h2>
            <p class="text-center text-sm text-gray-500">Join our platform. A verification code will be sent via WhatsApp by Happywed after you submit the form.</p>
        </div>

        <form action="{{ route('customer.register.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4 w-full">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Mobile Number</label>
                    <input type="text" name="mobile_number" value="{{ old('mobile_number') }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    @error('mobile_number') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Profile Avatar Image</label>
                <input type="file" name="profile_image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition shadow">Register Account</button>
        </form>
        <p class="text-center text-sm text-gray-600 mt-4">Already signed up? <a href="{{ route('customer.login') }}" class="text-blue-600 font-medium hover:underline">Log In</a></p>
    </div>
</body>
</html>