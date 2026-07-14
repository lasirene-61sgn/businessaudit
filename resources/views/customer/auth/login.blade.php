<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-sm border w-full max-w-md mx-auto">
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('logo.png') }}" alt="La Sirene Logo" class="h-12 object-contain mb-4">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Business Health Check Up</h2>
            <p class="text-center text-sm text-gray-500">Access your portal using your phone number.</p>
        </div>

        <form action="{{ route('customer.login.submit') }}" method="POST" class="space-y-4 w-full">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Mobile Number</label>
                <input type="text" name="mobile_number" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Enter mobile number" required autofocus>
                @error('mobile_number') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="••••••••" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition shadow">Sign In</button>
        </form>
        <p class="text-center text-sm text-gray-600 mt-4">Don't have an account? <a href="{{ route('customer.register') }}" class="text-blue-600 font-medium hover:underline">Sign Up</a></p>
    </div>
</body>
</html>