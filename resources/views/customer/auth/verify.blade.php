<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Mobile Number</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-xl shadow-sm border w-full max-w-md mx-auto">
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('logo.png') }}" alt="La Sirene Logo" class="h-12 object-contain mb-4">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Security Verification</h2>
            <p class="text-center text-sm text-gray-500">
                Please enter the 6-digit OTP confirmation code sent to your Whatsapp Number by happywed to activate your account.
            </p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded-lg text-sm mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('info'))
            <div class="bg-blue-50 border border-blue-200 text-blue-700 p-3 rounded-lg text-sm mb-4">
                {{ session('info') }}
            </div>
        @endif

        <form action="{{ route('customer.verify.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="otp" class="block text-sm font-semibold text-gray-700 mb-2 text-center">Enter 6-Digit OTP</label>
                <input 
                    type="text" 
                    name="otp" 
                    id="otp"
                    maxlength="6"
                    placeholder="000000" 
                    class="w-full text-center tracking-widest text-2xl font-mono font-bold px-3 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('otp') border-red-500 @enderror" 
                    required 
                    autofocus
                >
                @error('otp') 
                    <span class="text-red-500 text-xs mt-1 text-center block font-medium">{{ $message }}</span> 
                @enderror
            </div>

            <button 
                type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition shadow"
            >
                Verify & Open Dashboard
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('customer.login') }}" class="text-xs text-gray-400 hover:text-gray-600 underline">
                Back to Login screen
            </a>
        </div>
    </div>

</body>
</html>