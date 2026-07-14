@extends('admin.layouts.app')

@section('title', 'Modify Audit Question')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.audit.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition">
            ← Cancel and Return back to Panel List
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">🛠️ Edit Assessment Question Settings</h1>
    </div>

    <form action="{{ route('admin.audit.question.update', $question->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 space-y-4">
            <h2 class="text-base font-bold text-gray-900 border-b pb-2">1. Core Operational Properties</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Category Segment Group</label>
                    <input type="text" disabled value="{{ $question->category->name }}" class="w-full bg-gray-100 border border-gray-200 p-2.5 rounded-lg text-sm text-gray-500 font-semibold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Visibility Status</label>
                    <select name="is_active" class="w-full border border-gray-300 p-2.5 rounded-lg bg-white text-sm outline-hidden focus:border-indigo-500">
                        <option value="1" {{ $question->is_active ? 'selected' : '' }}>Active Live</option>
                        <option value="0" {{ !$question->is_active ? 'selected' : '' }}>Disabled/Draft</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Question Evaluation Text</label>
                <textarea name="question_text" required rows="2" class="w-full border border-gray-300 p-3 rounded-lg text-sm outline-hidden focus:border-indigo-500">{{ $question->question_text }}</textarea>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex justify-between items-center border-b pb-2 mb-4">
                <h2 class="text-base font-bold text-gray-900">2. Target Options Mapping Nodes</h2>
                <button type="button" onclick="appendEditOptionRow()" class="px-3 py-1 bg-indigo-50 text-indigo-700 font-semibold rounded text-xs hover:bg-indigo-100 transition cursor-pointer">
                    + Append Options Configuration Row
                </button>
            </div>

            <div id="options-payload-root" class="space-y-4">
                @foreach($question->options as $index => $option)
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl space-y-3 relative">
                        <input type="hidden" name="options[{{ $index }}][id]" value="{{ $option->id }}">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Option String Text</label>
                                <input type="text" name="options[{{ $index }}][option_text]" value="{{ $option->option_text }}" required class="w-full bg-white border border-gray-300 px-3 py-1.5 text-sm rounded-lg outline-hidden">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Points Value Weight</label>
                                <input type="number" name="options[{{ $index }}][score]" value="{{ $option->score }}" required class="w-full bg-white border border-gray-300 px-3 py-1.5 text-sm rounded-lg outline-hidden">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">XP Progression Reward</label>
                                <input type="number" name="options[{{ $index }}][xp]" value="{{ $option->xp }}" required class="w-full bg-white border border-gray-300 px-3 py-1.5 text-sm rounded-lg outline-hidden">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Custom Action Suggestion Advice</label>
                            <textarea name="options[{{ $index }}][improvement_suggestion]" rows="2" class="w-full bg-white border border-gray-300 p-2 text-sm rounded-lg outline-hidden">{{ $option->improvement_suggestion }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.audit.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Discard Changes</a>
            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-sm transition cursor-pointer">
                Update Metric Structural Layout
            </button>
        </div>
    </form>
</div>

<script>
    let activeRowIndex = {{ $question->options->count() }};
    function appendEditOptionRow() {
        const rootContainer = document.getElementById('options-payload-root');
        const generatedSnippet = `
            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl space-y-3 relative">
                <button type="button" onclick="this.parentElement.remove()" class="absolute top-3 right-3 text-xs text-red-500 hover:underline font-bold cursor-pointer">✕ Drop</button>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Option String Text</label>
                        <input type="text" name="options[\${activeRowIndex}][option_text]" required class="w-full bg-white border border-gray-300 px-3 py-1.5 text-sm rounded-lg outline-hidden">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Points Value Weight</label>
                        <input type="number" name="options[\${activeRowIndex}][score]" value="0" required class="w-full bg-white border border-gray-300 px-3 py-1.5 text-sm rounded-lg outline-hidden">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">XP Progression Reward</label>
                        <input type="number" name="options[\${activeRowIndex}][xp]" value="0" required class="w-full bg-white border border-gray-300 px-3 py-1.5 text-sm rounded-lg outline-hidden">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Custom Action Suggestion Advice</label>
                    <textarea name="options[\${activeRowIndex}][improvement_suggestion]" rows="2" class="w-full bg-white border border-gray-300 p-2 text-sm rounded-lg outline-hidden" placeholder="Write advice patterns..."></textarea>
                </div>
            </div>
        `;
        rootContainer.insertAdjacentHTML('beforeend', generatedSnippet);
        activeRowIndex++;
    }
</script>
@endsection