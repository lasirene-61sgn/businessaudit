<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 font-sans">

    <nav class="bg-indigo-700 text-white shadow-md px-6 py-4 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex flex-col md:flex-row items-center gap-6">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-xl font-bold tracking-wider whitespace-nowrap">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-8 object-contain">
                <span>ADMIN CONTROL PANEL</span>
            </a>
            
            <!-- Added Navigation Items Link Set -->
            <div class="flex items-center gap-4 border-t md:border-t-0 pt-2 md:pt-0 border-indigo-600/50 w-full md:w-auto">
                <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-indigo-200 hover:text-white transition">
                    Dashboard
                </a>
                <a href="{{ route('admin.audit.index') }}" class="text-sm font-semibold {{ request()->routeIs('admin.audit.index', 'admin.audit.question.*') ? 'text-white bg-indigo-800 px-3 py-1.5 rounded-md' : 'text-indigo-200 hover:text-white' }} transition">
                    Audit Management
                </a>
                <a href="{{ route('admin.audit.customer_audits') }}" class="text-sm font-semibold {{ request()->routeIs('admin.audit.customer_*') ? 'text-white bg-indigo-800 px-3 py-1.5 rounded-md' : 'text-indigo-200 hover:text-white' }} transition">
                    Customer Audits
                </a>
            </div>
        </div>

        <div class="flex items-center justify-between gap-4 w-full md:w-auto border-t md:border-t-0 pt-3 md:pt-0 border-indigo-600/50">
            <span class="text-sm">Logged in as: <strong>{{ auth()->guard('admin')->user()->name }}</strong></span>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 text-xs font-semibold rounded transition cursor-pointer">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 shadow-sm">
                <ul class="list-disc pl-5 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

</body>
</html>