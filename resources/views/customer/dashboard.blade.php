<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 p-4 md:p-8">
    <div class="max-w-4xl mx-auto mb-6 flex justify-center md:justify-start">
        <img src="{{ asset('logo.png') }}" alt="La Sirene Logo" class="h-12 object-contain">
    </div>
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-sm border flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-4">
            @if(auth()->guard('customer')->user()->profile_image)
                <img src="{{ asset('storage/' . auth()->guard('customer')->user()->profile_image) }}" class="w-16 h-16 rounded-full object-cover border">
            @else
                <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-700 font-bold text-xl flex items-center justify-center border">
                    {{ strtoupper(substr(auth()->guard('customer')->user()->name, 0, 2)) }}
                </div>
            @endif
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ auth()->guard('customer')->user()->name }}!</h1>
                <p class="text-sm text-gray-500">Your account mobile identity is verified and active.</p>
            </div>
        </div>
        <form action="{{ route('customer.logout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-medium px-4 py-2 rounded-lg text-sm transition">Logout</button>
        </form>
    </div>
</body>
</html>