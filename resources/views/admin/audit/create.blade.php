@extends('admin.layouts.app')

@section('title', 'Add Audit Question')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.audit.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition">
            ← Return to Dashboard Overview
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">✨ Create Assessment Question Matrix</h1>
    </div>

    <form action="{{ route('admin.audit.question.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 space-y-4">
            <h2 class="text-base font-bold text-gray-900 border-b pb-2">1. Target Parameters</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Target Category Stage Pillar</label>
                    <select name="category_id" required class="w-full border border-gray-300 p-2.5 rounded-lg bg-white text-sm outline-hidden focus:border-indigo-500">
                        <option value="">-- Choose Category Target --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Visibility Status</label>
                    <select name="is_active" class="w-full border border-gray-300 p-2.5 rounded-lg bg-white text-sm outline-hidden focus:border-indigo-500">
                        <option value="1">Active Live</option>
                        <option value="0">Disabled/Draft</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Evaluation Question Input Text</label>
                <textarea name="question_text" required rows="2" class="w-full border border-gray-300 p-3 rounded-lg text-sm outline-hidden focus:border-indigo-500" placeholder="e.g. Do you audit active structural security policies monthly?"></textarea>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex justify-between items-center border-b pb-2 mb-4">
                <h2 class="text-base font-bold text-gray-900">2. Configured Choice Blocks ($N$)</h2>
                <button type="button" onclick="addDynamicOptionRow()" class="px-3 py-1 bg-indigo-50 text-indigo-700 font-semibold rounded text-xs hover:bg-indigo-100 transition cursor-pointer">
                    + Add Selection Choice Row
                </button>
            </div>

            <div id="options-payload-root" class="space-y-4">
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Option String Text</label>
                            <input type="text" name="options[0][option_text]" required class="w-full bg-white border border-gray-300 px-3 py-1.5 text-sm rounded-lg outline-hidden" placeholder="e.g. Yes">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Points Metric Weight</label>
                            <input type="number" name="options[0][score]" value="10" required class="w-full bg-white border border-gray-300 px-3 py-1.5 text-sm rounded-lg outline-hidden">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">XP Progression Reward</label>
                            <input type="number" name="options[0][xp]" value="10" required class="w-full bg-white border border-gray-300 px-3 py-1.5 text-sm rounded-lg outline-hidden">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Custom Action Report Suggestion Advice</label>
                        <textarea name="options[0][improvement_suggestion]" rows="2" class="w-full bg-white border border-gray-300 p-2 text-sm rounded-lg outline-hidden" placeholder="Dynamic advisory response text to populate inside final generated PDF panel documentation..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.audit.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancel</a>
            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-sm transition cursor-pointer">
                Publish Audit Parameter
            </button>
        </div>
    </form>
</div>

<script>
    let activeRowIndex = 1;
    function addDynamicOptionRow() {
        const rootContainer = document.getElementById('options-payload-root');
        const generatedSnippet = `
            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl space-y-3 relative">
                <button type="button" onclick="this.parentElement.remove()" class="absolute top-3 right-3 text-xs text-red-500 hover:underline font-bold cursor-pointer">✕ Drop</button>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Option String Text</label>
                        <input type="text" name="options[${activeRowIndex}][option_text]" required class="w-full bg-white border border-gray-300 px-3 py-1.5 text-sm rounded-lg outline-hidden">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Points Metric Weight</label>
                        <input type="number" name="options[${activeRowIndex}][score]" value="0" required class="w-full bg-white border border-gray-300 px-3 py-1.5 text-sm rounded-lg outline-hidden">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">XP Progression Reward</label>
                        <input type="number" name="options[${activeRowIndex}][xp]" value="0" required class="w-full bg-white border border-gray-300 px-3 py-1.5 text-sm rounded-lg outline-hidden">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Custom Action Report Suggestion Advice</label>
                    <textarea name="options[${activeRowIndex}][improvement_suggestion]" rows="2" class="w-full bg-white border border-gray-300 p-2 text-sm rounded-lg outline-hidden" placeholder="Write advice patterns..."></textarea>
                </div>
            </div>
        `;
        rootContainer.insertAdjacentHTML('beforeend', generatedSnippet);
        activeRowIndex++;
    }
</script>
@endsection