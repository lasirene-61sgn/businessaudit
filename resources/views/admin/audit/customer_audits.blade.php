@extends('admin.layouts.app')

@section('title', 'Customer Audits')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Customer Audits Overview</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-bold uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Business</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Score</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($audits as $audit)
                    <tr class="hover:bg-indigo-50/50 transition">
                        <td class="px-6 py-4 font-mono text-gray-500">#{{ $audit->id }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $audit->user->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-gray-500">{{ $audit->user->email ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-700 font-semibold">{{ $audit->business_name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ \Carbon\Carbon::parse($audit->created_at)->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            @if($audit->status === 'completed')
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-md uppercase tracking-wider">Completed</span>
                            @else
                                <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-md uppercase tracking-wider">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-bold text-indigo-600">{{ $audit->total_score ?? '-' }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            @if($audit->status === 'completed')
                                <a href="{{ route('admin.audit.customer_report', $audit->id) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs border border-indigo-200 px-3 py-1.5 rounded-lg hover:bg-indigo-50 transition">
                                    View Report
                                </a>
                                <a href="{{ route('admin.audit.customer_pdf', $audit->id) }}" class="text-orange-600 hover:text-orange-900 font-semibold text-xs border border-orange-200 px-3 py-1.5 rounded-lg hover:bg-orange-50 transition">
                                    Download PDF
                                </a>
                            @else
                                <span class="text-gray-400 text-xs italic">Incomplete</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-400 italic">No customer audits found in the database.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
