<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Panel - Business Audit Hub</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 text-gray-800 p-4 md:p-8">

    <div class="max-w-4xl mx-auto bg-white rounded-2xl border border-amber-400 p-6 md:p-12 shadow-sm relative overflow-hidden">

        <!-- Logout Button -->
        <div class="absolute top-4 right-4">
            <form action="{{ route('customer.logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs font-bold text-gray-500 hover:text-red-500 border border-gray-200 hover:border-red-300 px-4 py-2 rounded-lg transition-colors bg-white shadow-sm">
                    🚪 Logout
                </button>
            </form>
        </div>
        
        <!-- Header Branding Module -->
        <div class="text-center space-y-3 mb-10">
            <div class="flex justify-center mb-2">
                <img src="{{ asset('logo.png') }}" alt="La Sirene Logo" class="h-16 object-contain">
            </div>
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Business <span class="text-amber-500">Health Check Up</span></h1>
            <p class="text-gray-500 text-sm max-w-lg mx-auto">
                A professional health check for your business. Answer core targeted verification queries across multiple business areas to unlock personalized improvement suggestions and clean reports.
            </p>
        </div>

        <!-- Metric Badges Info bar Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-center text-xs font-bold text-gray-700 mb-8">
            <div class="bg-gray-100 p-2.5 rounded-full">⚡ Earn scalable XP</div>
            <div class="bg-gray-100 p-2.5 rounded-full">⏱️ ~15 mins duration</div>
            <div class="bg-gray-100 p-2.5 rounded-full">🔒 100% Secure</div>
            <div class="bg-gray-100 p-2.5 rounded-full">📄 Download PDF Report</div>
        </div>

        <!-- Dynamic Category Preview Modules Grid Lists -->
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-1">What's Covered</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            @foreach($categories as $cat)
                <div class="bg-white p-4 border border-gray-100 rounded-xl shadow-xs text-center space-y-1">
                    <div class="text-lg">📁</div>
                    <h4 class="font-bold text-gray-900 text-sm">{{ $cat->name }}</h4>
                    <p class="text-gray-400 text-xs">{{ $cat->questions_count }} dynamic evaluation metrics</p>
                </div>
            @endforeach
        </div>

        <!-- Navigation Entry Interaction Controls -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t pt-6">
            <!-- Modal trigger wrapper -->
            <button onclick="toggleSetupModal(true)" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-6 rounded-xl shadow-sm transition text-center cursor-pointer">
                ▶ Start New Business Audit
            </button>

            @if($savedDraft)
                <a href="{{ route('customer.audit.step', [$savedDraft->id, $categories->first()->id]) }}" class="w-full bg-white border-2 border-gray-200 hover:bg-gray-50 text-gray-700 font-bold py-3 px-6 rounded-xl transition text-center">
                    📂 Load Saved Draft System
                </a>
            @else
                <div class="bg-gray-50 text-gray-400 text-xs font-medium rounded-xl flex items-center justify-center p-3 border border-dashed border-gray-200">
                    No active draft variations saved in layout log history.
                </div>
            @endif
        </div>
    </div>

    <!-- Historical Finished Document Archives List -->
    @if($completedAudits->isNotEmpty())
        <div class="max-w-4xl mx-auto mt-8 bg-white rounded-xl border p-6 shadow-2xs">
            <h3 class="font-bold text-gray-900 mb-3 text-sm">Archived Audit PDF Document Summaries</h3>
            <div class="divide-y divide-gray-100">
                @foreach($completedAudits as $historyItem)
                    <div class="py-3 flex justify-between items-center text-sm">
                        <div>
                            <p class="font-bold text-slate-800">{{ $historyItem->business_name }}</p>
                            <p class="text-xs text-gray-400 font-mono">Completed: {{ $historyItem->updated_at->format('d-M-Y') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('customer.audit.report', $historyItem->id) }}" class="text-xs bg-gray-100 text-gray-700 font-semibold px-3 py-1 rounded hover:bg-gray-200">View Grid Report</a>
                            <a href="{{ route('customer.audit.pdf', $historyItem->id) }}" class="text-xs bg-indigo-50 text-indigo-600 font-bold px-3 py-1 rounded hover:bg-indigo-100">Download PDF</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Dynamic Modal Insertion Logic Module matching Image_482377.png fields layout -->
    <div id="setupFormModal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 z-50 overflow-y-auto">
        <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl border">
            <div class="bg-slate-900 text-white p-5 flex justify-between items-center sticky top-0">
                <div>
                    <span class="text-xs font-bold text-amber-400 uppercase tracking-wide">Step 1 of Setup</span>
                    <h2 class="text-lg font-bold">Business Information Record</h2>
                </div>
                <button onclick="toggleSetupModal(false)" class="text-gray-400 hover:text-white font-bold text-xl cursor-pointer">✕</button>
            </div>
            <form action="{{ route('customer.audit.start') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Business Name *</label>
                        <input type="text" name="business_name" required class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-hidden focus:border-amber-500" placeholder="e.g. Sunrise Cafe">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Owner / Contact Name *</label>
                        <input type="text" name="owner_name" required class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-hidden focus:border-amber-500" placeholder="e.g. Jane Smith">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Business Type</label>
                        <input type="text" name="business_type" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-hidden" placeholder="e.g. LLC / Partnership">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Industry / Sector</label>
                        <input type="text" name="industry" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-hidden" placeholder="e.g. Food & Beverage">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Years In Operation</label>
                        <input type="number" name="years_in_operation" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-hidden" placeholder="3">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">No. of Employees</label>
                        <input type="number" name="no_of_employees" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-hidden" placeholder="12">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Auditor Name</label>
                        <input type="text" name="auditor_name" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-hidden" placeholder="Self Assessment">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Audit Evaluation Date</label>
                        <input type="date" name="audit_date" value="{{ date('Y-m-d') }}" required class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-hidden">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Additional Notes</label>
                    <textarea name="additional_notes" rows="2" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-hidden" placeholder="Optional context descriptions..."></textarea>
                </div>
                <div class="flex justify-end gap-2 border-t pt-4">
                    <button type="button" onclick="toggleSetupModal(false)" class="px-4 py-2 border rounded-lg text-sm text-gray-600">Close</button>
                    <button type="submit" class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm rounded-lg">Continue to Question Sheets →</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSetupModal(show) {
            document.getElementById('setupFormModal').classList.toggle('hidden', !show);
        }
    </script>
</body>
</html>