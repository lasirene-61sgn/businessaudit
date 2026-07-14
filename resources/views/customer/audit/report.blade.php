<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Health Report</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f3f4f6; }
    </style>
</head>
<body class="text-gray-900 antialiased p-4 md:p-8">

    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Header Actions -->
        <div class="flex justify-between items-center bg-white p-4 border border-gray-200 rounded-xl shadow-sm">
            <a href="{{ route('customer.audit.dashboard') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-800 transition">← Back to Dashboard Hub</a>
            <a href="{{ route('customer.audit.pdf', $audit->id) }}" class="px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm rounded-lg shadow transition">
                📥 Download PDF Report
            </a>
        </div>

        <!-- Document Header (Mockup Page 1) -->
        <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm border-t-4 border-t-orange-500">
            <div class="flex items-center gap-3 mb-8 border-b pb-6">
                <img src="{{ asset('logo.png') }}" alt="La Sirene Logo" class="h-12 object-contain">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">La Sirene.</h1>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Business Health Checkup</p>
                </div>
            </div>

            <h2 class="text-3xl font-black text-gray-900 mb-1"><span class="text-orange-500">Business Health Checkup</span></h2>
            <!-- <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-6"> Business Health Checkup</p> -->

            <!-- Business Info Box -->
            <div class="border border-amber-300 bg-amber-50/20 rounded-xl p-6 mb-6">
                <h3 class="text-2xl font-black text-gray-900 mb-2">{{ $audit->business_name }}</h3>
                <div class="flex flex-wrap gap-x-4 gap-y-2 text-sm text-gray-600 mb-2 font-semibold">
                    <span>{{ $audit->owner_name }}</span> &middot;
                    <span>{{ $audit->business_type ?? 'Business' }}</span> &middot;
                    <span>{{ $audit->industry ?? 'Sector' }}</span>
                </div>
                <div class="flex flex-wrap gap-x-4 gap-y-2 text-xs text-gray-500">
                    <span>Audit Date: <strong class="text-gray-800">{{ $audit->audit_date }}</strong></span> &middot;
                    <span>Auditor: <strong class="text-gray-800">{{ $audit->auditor_name ?? 'N/A' }}</strong></span>
                </div>
                <div class="flex flex-wrap gap-x-4 gap-y-2 text-xs text-gray-500 mt-1">
                    <span>Years: <strong class="text-gray-800">{{ $audit->years_in_operation ?? 'N/A' }}</strong></span> &middot;
                    <span>Employees: <strong class="text-gray-800">{{ $audit->no_of_employees ?? 'N/A' }}</strong></span>
                </div>
            </div>

            <!-- Grade & Score Box -->
            <div class="flex flex-col md:flex-row gap-6">
                <div class="border border-amber-300 rounded-xl p-6 flex flex-col items-center justify-center min-w-[200px]">
                    <div class="text-6xl font-black text-blue-600 mb-1 leading-none">{{ $audit->total_score }}</div>
                    <div class="text-lg font-bold text-gray-400 mb-4">/{{ $maxTotalScore }}</div>
                    <div class="text-sm font-black text-gray-900">{{ $grade }}</div>
                    <div class="text-[10px] text-gray-500 font-semibold">{{ $overallPercentage }}% overall</div>
                </div>

                <div class="border border-gray-200 rounded-xl p-6 flex-1 flex flex-col justify-center">
                    <h4 class="text-xl font-black text-blue-600 mb-2">{{ $grade }}</h4>
                    <p class="text-sm text-gray-600 mb-6">{{ $gradeDesc }}</p>
                    
                    <div class="relative w-full h-8 bg-amber-50 rounded-full border border-amber-200 overflow-hidden flex items-center px-4">
                        <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-amber-100 to-amber-200" style="width: {{ $overallPercentage }}%;"></div>
                        <span class="relative text-xs font-bold text-amber-600 z-10">⚡ {{ $audit->total_xp }} XP Earned</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Progress Section (Mockup Page 2) -->
        <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm">
            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6 border-b-2 border-orange-500 pb-2 inline-flex items-center gap-2">
                📊 Score Summary
            </h3>
            
            <div class="space-y-6">
                @foreach($categoryScores as $cat)
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-sm font-bold text-gray-900">{{ $cat['name'] }}</span>
                        <span class="text-xs font-bold text-blue-600">{{ $cat['score'] }}/{{ $cat['max'] }} ({{ $cat['percentage'] }}%)</span>
                    </div>
                    <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full {{ $cat['percentage'] >= 70 ? 'bg-green-600' : ($cat['percentage'] >= 40 ? 'bg-orange-500' : 'bg-red-500') }} rounded-full" style="width: {{ $cat['percentage'] }}%;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Strengths & Areas to Improve (Mockup Page 2) -->
        <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm">
            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6 border-b-2 border-orange-500 pb-2 inline-flex items-center gap-2">
                ⚡ Strengths & Areas to Improve
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Strengths -->
                <div>
                    <h4 class="text-sm font-bold text-green-700 border-b border-gray-100 pb-2 mb-4">Strengths</h4>
                    <ul class="space-y-3">
                        @forelse($strengths as $strength)
                            <li class="flex gap-2 text-xs text-gray-600">
                                <span class="text-green-600">•</span> {{ $strength }}
                            </li>
                        @empty
                            <li class="text-xs text-gray-400 italic">No major strengths identified yet.</li>
                        @endforelse
                    </ul>
                </div>
                
                <!-- Areas to Improve -->
                <div>
                    <h4 class="text-sm font-bold text-orange-600 border-b border-gray-100 pb-2 mb-4">Areas to Improve</h4>
                    <ul class="space-y-3">
                        @forelse($improvements as $improvement)
                            <li class="flex gap-2 text-xs text-gray-600">
                                <span class="text-orange-500">•</span> {{ $improvement }}
                            </li>
                        @empty
                            <li class="text-xs text-gray-400 italic">No major weaknesses!</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Key Recommendations (Mockup Page 3) -->
        <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm mb-12">
            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6 border-b-2 border-orange-500 pb-2 inline-flex items-center gap-2">
                💡 Key Recommendations
            </h3>

            <div class="space-y-4">
                @forelse($recommendations as $rec)
                    <div class="border border-gray-200 rounded-lg p-5 border-l-4 {{ $rec['priority'] === 'HIGH' ? 'border-l-red-500' : 'border-l-orange-400' }} shadow-2xs">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">{{ $rec['category'] }}</span>
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider {{ $rec['priority'] === 'HIGH' ? 'bg-red-100 text-red-600' : 'bg-orange-100 text-orange-600' }}">
                                {{ $rec['priority'] }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed mt-2">{{ $rec['suggestion'] }}</p>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-400 text-sm italic border rounded-lg bg-gray-50">
                        No actionable recommendations found based on current responses.
                    </div>
                @endforelse
        </div>

        <!-- Detailed Audit Responses (Mockup Page 5 & 6) -->
        <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm mb-12">
            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6 border-b-2 border-orange-500 pb-2 inline-flex items-center gap-2">
                📋 Detailed Audit Responses
            </h3>

            <div class="space-y-8">
                @php
                    $groupedAnswers = $audit->answers->groupBy('question.category.name');
                    $questionNumber = 1;
                @endphp

                @foreach($groupedAnswers as $categoryName => $answers)
                    <div>
                        <h4 class="text-md font-bold text-gray-900 border-l-4 border-orange-500 pl-3 mb-4 bg-gray-50 py-2">{{ $categoryName }}</h4>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-gray-100 text-gray-500 font-bold uppercase">
                                    <tr>
                                        <th class="px-4 py-2 w-12 rounded-tl-lg">#</th>
                                        <th class="px-4 py-2 w-1/2">Question</th>
                                        <th class="px-4 py-2">Response</th>
                                        <th class="px-4 py-2 rounded-tr-lg">Notes</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($answers as $answer)
                                        <tr class="hover:bg-gray-50/50">
                                            <td class="px-4 py-3 font-bold text-gray-400">{{ $questionNumber++ }}</td>
                                            <td class="px-4 py-3 font-semibold text-gray-700">{{ $answer->question->question_text }}</td>
                                            <td class="px-4 py-3">
                                                @php
                                                    $isMax = $answer->option->score > 0 && $answer->option->score >= $answer->question->options->max('score');
                                                @endphp
                                                <span class="px-2 py-1 rounded-full text-[10px] font-bold {{ $isMax ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                                    {{ $answer->option->option_text }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-gray-500 italic">{{ $answer->notes ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Completion Message -->
        <div class="bg-orange-50 border-l-4 border-orange-500 p-6 rounded-r-xl shadow-sm mb-12">
            <h3 class="text-lg font-bold text-orange-800 mb-1">All steps have been completed!</h3>
            <p class="text-orange-700 font-medium">For any queries, please contact <span class="font-black">7418111000</span> (WhatsApp and calls are available on this number).</p>
        </div>

    </div>

    @if(session('success'))
        <!-- Completion Modal Popup -->
        <div id="completionModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity">
            <div class="bg-white rounded-2xl shadow-xl p-8 max-w-sm w-full mx-4 transform transition-all scale-100 border-t-4 border-orange-500">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-xl font-black text-center text-gray-900 mb-2">Business Health Check up!</h3>
                <p class="text-center text-sm text-gray-600 mb-6">
                    All steps have been completed successfully.<br><br>
                    For any queries, please contact <br>
                    <strong class="text-gray-900 text-lg">7418111000</strong><br>
                    <span class="text-xs font-semibold text-gray-500">(WhatsApp & Calls are available)</span>
                </p>
                <button onclick="document.getElementById('completionModal').style.display='none'" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-xl transition-colors">
                    View My Report
                </button>
            </div>
        </div>
    @endif

</body>
</html>