@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-bold">Ticket #{{ $ticket->id }}</h1>
                <span class="px-2 py-1 text-xs rounded {{ $ticket->trashed() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                {{ $ticket->trashed() ? 'Deleted' : 'Active' }}
            </span>
            </div>
            <div class="flex gap-2">
                @if(!$ticket->trashed())
                    @can('update', $ticket)
                        <a href="{{ route('tickets.edit', $ticket) }}" class="bg-blue-500 text-white px-4 py-2 rounded">Edit</a>
                    @endcan
                    @if(auth()->user()->isTechnician() && $ticket->assigned_to_id !== auth()->id())
                        <form action="{{ route('tickets.assign-self', $ticket) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="bg-green-500 text-white px-4 py-2 rounded">Assign to Me</button>
                        </form>
                    @endif
                @endif
                <a href="{{ route('tickets.index') }}" class="bg-gray-300 px-4 py-2 rounded">Back</a>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div><strong>Affected User:</strong> {{ $ticket->affectedUser->name }}</div>
            <div><strong>Assigned To:</strong> {{ $ticket->assignedTo?->name ?? 'Unassigned' }}</div>
            <div><strong>Received:</strong> {{ $ticket->received_date->format('Y-m-d') }}</div>
            <div><strong>Created:</strong> {{ $ticket->created_at->format('Y-m-d H:i') }}</div>
        </div>

        <div class="mb-4">
            <strong>Problem:</strong>
            <p class="mt-2 bg-gray-50 p-4 rounded">{{ $ticket->problem_description }}</p>
        </div>

        @if($ticket->additional_notes)
            <div>
                <strong>Notes:</strong>
                <p class="mt-2 bg-gray-50 p-4 rounded">{{ $ticket->additional_notes }}</p>
            </div>
        @endif
    </div>

    <!-- Comments Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Comments ({{ $ticket->comments->count() }})</h2>

        @if(!$ticket->trashed() && !auth()->user()->isRegular())
            <div class="mb-6" x-data="{ open: false }">
                <button @click="open = !open" class="bg-blue-500 text-white px-4 py-2 rounded">Add Comment</button>
                <div x-show="open" x-cloak class="mt-4">
                    <form action="{{ route('comments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                        <textarea name="body" maxlength="255" required rows="3" class="w-full border rounded px-3 py-2 mb-2" placeholder="Max 255 characters"></textarea>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Submit</button>
                        <button type="button" @click="open = false" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
                    </form>
                </div>
            </div>
        @endif

        <div class="space-y-4">
            @forelse($ticket->comments as $comment)
                <div class="bg-gray-50 p-4 rounded">
                    <div class="flex justify-between mb-2">
                        <strong>{{ $comment->author->name }}</strong>
                        <span class="text-sm text-gray-600">{{ $comment->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    <p>{{ $comment->body }}</p>
                </div>
            @empty
                <p class="text-gray-500">No comments yet</p>
            @endforelse
        </div>
    </div>

    <style>[x-cloak] { display: none !important; }</style>
@endsection
