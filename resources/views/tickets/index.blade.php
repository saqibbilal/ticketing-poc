@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Tickets</h1>
            @can('create', App\Models\Ticket::class)
                <a href="{{ route('tickets.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Create Ticket
                </a>
            @endcan
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <table class="w-full">
            <thead>
            <tr class="bg-gray-50">
                <th class="px-4 py-2 text-left">#</th>
                <th class="px-4 py-2 text-left">Affected User</th>
                <th class="px-4 py-2 text-left">Assigned To</th>
                <th class="px-4 py-2 text-left">Comments</th>
                <th class="px-4 py-2 text-left">Status</th>
                <th class="px-4 py-2 text-left">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tickets as $ticket)
                <tr class="border-t {{ $ticket->trashed() ? 'bg-gray-50' : '' }}">
                    <td class="px-4 py-2">
                        <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600">#{{ $ticket->id }}</a>
                    </td>
                    <td class="px-4 py-2">{{ $ticket->affectedUser->name }}</td>
                    <td class="px-4 py-2">{{ $ticket->assignedTo?->name ?? 'Unassigned' }}</td>
                    <td class="px-4 py-2">{{ $ticket->comments->count() }}</td>
                    <td class="px-4 py-2">
                    <span class="px-2 py-1 text-xs rounded {{ $ticket->trashed() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        {{ $ticket->trashed() ? 'Deleted' : 'Active' }}
                    </span>
                    </td>
                    <td class="px-4 py-2">
                        @if($ticket->trashed())
                            @can('restore', $ticket)
                                <form action="{{ route('tickets.restore', $ticket->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button class="text-green-600">Restore</button>
                                </form>
                            @endcan
                        @else
                            @can('update', $ticket)
                                <a href="{{ route('tickets.edit', $ticket) }}" class="text-blue-600">Edit</a>
                            @endcan
                            @can('delete', $ticket)
                                <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600" onclick="return confirm('Delete?')">Delete</button>
                                </form>
                            @endcan
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
