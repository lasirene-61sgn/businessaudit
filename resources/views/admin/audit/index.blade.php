@extends('admin.layouts.app')

@section('title', 'Manage Business Audits')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">📋 Audit Strategy Management</h1>
            <p class="text-sm text-gray-500 mt-1">Configure structural pillars, question sets, and custom improvement suggestion report sheets.</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="toggleCategoryModal(true)" class="px-4 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 font-medium rounded-lg text-sm hover:bg-indigo-100 transition cursor-pointer">
                + New Category
            </button>
            <a href="{{ route('admin.audit.question.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-sm transition shadow-xs">
                + Add Audit Question
            </a>
        </div>
    </div>
</div>

<div class="space-y-8">
    @foreach($categories as $category)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded text-xs font-mono">Order #{{ $category->sort_order }}</span>
                        {{ $category->name }}
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $category->description ?? 'No extra descriptions added.' }}</p>
                </div>
                <span class="text-xs font-semibold px-3 py-1 bg-gray-200 text-gray-700 rounded-full">
                    {{ $category->questions->count() }} Questions
                </span>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($category->questions as $question)
                    <div class="p-6 hover:bg-gray-50/40 transition">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                            <div class="flex-1 space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Active Choice Matrix</span>
                                    @if(!$question->is_active)
                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-amber-100 text-amber-700 rounded">Draft Mode</span>
                                    @endif
                                </div>
                                <p class="font-semibold text-gray-900 text-base">{{ $question->question_text }}</p>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-2">
                                    @foreach($question->options as $option)
                                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-xs">
                                            <div class="flex justify-between items-center font-bold text-gray-800 mb-1">
                                                <span>🔹 {{ $option->option_text }}</span>
                                                <span class="text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded font-mono">+{{ $option->xp }} XP / +{{ $option->score }} Pts</span>
                                            </div>
                                            @if($option->improvement_suggestion)
                                                <p class="text-gray-500 italic mt-1 line-clamp-2">"{{ $option->improvement_suggestion }}"</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex items-center gap-2 lg:self-start">
                                <a href="{{ route('admin.audit.question.edit', $question->id) }}" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg text-xs hover:bg-gray-50 transition shadow-xs">
                                    Modify
                                </a>
                                <form action="{{ route('admin.audit.question.destroy', $question->id) }}" method="POST" onsubmit="return confirm('Permanently drop this question block?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-50 border border-red-200 text-red-600 font-medium rounded-lg text-xs hover:bg-red-100 transition cursor-pointer">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-gray-400 italic">
                        No metric configurations loaded under this sequence branch.
                    </div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>

<div id="categoryModal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-xl shadow-xl border w-full max-w-md overflow-hidden">
        <div class="bg-indigo-700 text-white p-4 flex justify-between items-center">
            <h3 class="font-bold">Add Dynamic Category Pillar</h3>
            <button onclick="toggleCategoryModal(false)" class="text-white hover:text-gray-200 font-bold text-xl cursor-pointer">✕</button>
        </div>
        <form action="{{ route('admin.audit.category.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Category Name</label>
                <input type="text" name="name" required class="w-full border border-gray-300 rounded-lg p-2 text-sm outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="e.g. Operations & Compliance">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Short Context/Description</label>
                <textarea name="description" rows="2" class="w-full border border-gray-300 rounded-lg p-2 text-sm outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="Brief target parameters details..."></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Sorting Index Order</label>
                <input type="number" name="sort_order" value="0" required class="w-full border border-gray-300 rounded-lg p-2 text-sm outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            <div class="flex items-center justify-end gap-2 pt-2 border-t">
                <button type="button" onclick="toggleCategoryModal(false)" class="px-3 py-1.5 border rounded-lg text-sm text-gray-600">Close</button>
                <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-sm">Save Category</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleCategoryModal(show) {
        document.getElementById('categoryModal').classList.toggle('hidden', !show);
    }
</script>
@endsection