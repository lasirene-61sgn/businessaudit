<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Step - {{ $category->name }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 text-gray-800 p-4 md:p-8">

    <div class="max-w-4xl mx-auto space-y-6">
        
        <div class="flex justify-center md:justify-start">
            <img src="{{ asset('logo.png') }}" alt="La Sirene Logo" class="h-10 object-contain">
        </div>
        
        <div class="flex justify-between items-center bg-white border border-gray-200 p-4 rounded-xl shadow-xs">
            <div>
                <span class="text-xs font-bold bg-amber-100 text-amber-800 px-2.5 py-1 rounded-sm uppercase font-mono">
                    Step {{ $currentStepIndex }} of {{ $totalSteps }}
                </span>
                <h2 class="text-xl font-black mt-1 text-slate-900">{{ $category->name }}</h2>
            </div>
            @if($category->description)
                <p class="text-xs font-medium text-gray-400 max-w-xs text-right hidden sm:block">
                    {{ $category->description }}
                </p>
            @endif
        </div>

        <form action="{{ route('customer.audit.step.save', [$audit->id, $category->id]) }}" method="POST" class="space-y-4" onsubmit="
            const btn = this.querySelector('button[type=submit]');
            btn.style.pointerEvents = 'none';
            btn.classList.add('opacity-75');
            btn.innerHTML = `
                <svg class='animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24'>
                    <circle class='opacity-25' cx='12' cy='12' r='10' stroke='currentColor' stroke-width='4'></circle>
                    <path class='opacity-75' fill='currentColor' d='M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z'></path>
                </svg>
                Saving & Proceeding...
            `;
            return true;
        ">
            @csrf
            
            @foreach($category->questions as $questionIndex => $question)
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-2xs space-y-4">
                    <div class="flex justify-between items-start gap-2">
                        <h4 class="font-bold text-gray-900 text-base">
                            Q{{ $questionIndex + 1 }} — {{ $question->question_text }}
                        </h4>
                        <span class="text-[10px] font-bold bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-sm whitespace-nowrap">
                            Max Rewards
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @foreach($question->options as $option)
                            <label class="border border-gray-200 rounded-xl p-4 flex flex-col items-center justify-center text-center space-y-2 cursor-pointer transition hover:bg-slate-50 relative has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/40 group">
                                
                                <input type="radio" 
                                       name="answers[{{ $question->id }}]" 
                                       value="{{ $option->id }}" 
                                       required 
                                       {{ (isset($savedAnswers[$question->id]) && $savedAnswers[$question->id] == $option->id) ? 'checked' : '' }}
                                       class="absolute top-3 left-3 accent-indigo-600">
                                
                                <span class="font-bold text-sm text-gray-800 pt-2 block">
                                    {{ $option->option_text }}
                                </span>
                                <span class="text-[11px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded">
                                    +{{ $option->xp }} XP
                                </span>
                            </label>
                        @endforeach
                    </div>

                    <div class="pt-2">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1">
                            Additional Context / Feedback Notes
                        </label>
                        <textarea name="notes[{{ $question->id }}]" 
                                  rows="1" 
                                  class="w-full border border-gray-200 p-2 rounded-lg text-xs outline-hidden focus:border-indigo-500 placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 bg-gray-50/30" 
                                  placeholder="Optional notes/context summaries...">{{ $savedNotes[$question->id] ?? '' }}</textarea>
                    </div>
                </div>
            @endforeach

            <div class="flex justify-between items-center border-t border-gray-200 pt-4 mt-6">
                <a href="{{ route('customer.dashboard') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-900 transition">
                    Save & Exit to Dashboard
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg text-sm transition cursor-pointer shadow-sm flex items-center justify-center">
                    Save and Proceed Next Step →
                </button>
            </div>
        </form>
    </div>

</body>
</html>