@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded">
                <div class="text-sm text-gray-600">Total Tickets</div>
                <div class="text-2xl font-bold">{{ \App\Models\Ticket::withTrashed()->count() }}</div>
            </div>
            <div class="bg-green-50 p-4 rounded">
                <div class="text-sm text-gray-600">Active Tickets</div>
                <div class="text-2xl font-bold">{{ \App\Models\Ticket::count() }}</div>
            </div>
            <div class="bg-red-50 p-4 rounded">
                <div class="text-sm text-gray-600">Deleted Tickets</div>
                <div class="text-2xl font-bold">{{ \App\Models\Ticket::onlyTrashed()->count() }}</div>
            </div>
            <div class="bg-yellow-50 p-4 rounded">
                <div class="text-sm text-gray-600">Unassigned</div>
                <div class="text-2xl font-bold">{{ \App\Models\Ticket::whereNull('assigned_to_id')->count() }}</div>
            </div>
        </div>

        <!-- All Tickets Table -->
        <h2 class="text-xl font-bold mb-4">All Tickets</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-left">Ticket #</th>
                    <th class="px-4 py-2 text-left">Affected User</th>
                    <th class="px-4 py-2 text-left">Assigned To</th>
                    <th class="px-4 py-2 text-left">Comments</th>
                    <th class="px-4 py-2 text-left">Status</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $tickets = \App\Models\Ticket::with(['affectedUser', 'assignedTo', 'comments'])
                        ->withTrashed()
                        ->latest()
                        ->get();
                @endphp
                @forelse($tickets as $ticket)
                    <tr class="border-t {{ $ticket->trashed() ? 'bg-gray-50' : '' }}">
                        <td class="px-4 py-2">
                            <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-800">#{{ $ticket->id }}</a>
                        </td>
                        <td class="px-4 py-2">{{ $ticket->affectedUser->name }}</td>
                        <td class="px-4 py-2">{{ $ticket->assignedTo?->name ?? 'Unassigned' }}</td>
                        <td class="px-4 py-2">{{ $ticket->comments->count() }}</td>
                        <td class="px-4 py-2">
                        <span class="px-2 py-1 text-xs rounded {{ $ticket->trashed() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $ticket->trashed() ? 'Deleted' : 'Active' }}
                        </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">No tickets found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
