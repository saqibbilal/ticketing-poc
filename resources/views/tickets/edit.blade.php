@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Edit Ticket #{{ $ticket->id }}</h1>

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('tickets.update', $ticket) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-4">
                <label class="block mb-2">Affected User *</label>
                <select name="affected_user_id" required class="w-full border rounded px-3 py-2">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $ticket->affected_user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Problem Description *</label>
                <textarea name="problem_description" required rows="4" class="w-full border rounded px-3 py-2">{{ $ticket->problem_description }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Received Date *</label>
                <input type="date" name="received_date" value="{{ $ticket->received_date->format('Y-m-d') }}" max="{{ date('Y-m-d') }}" required class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block mb-2">Additional Notes (max 128)</label>
                <input type="text" name="additional_notes" value="{{ $ticket->additional_notes }}" maxlength="128" class="w-full border rounded px-3 py-2">
            </div>

            @if(auth()->user()->isAdmin())
                <div class="mb-4">
                    <label class="block mb-2">Assign To</label>
                    <select name="assigned_to_id" class="w-full border rounded px-3 py-2">
                        <option value="">Unassigned</option>
                        @foreach($technicians as $tech)
                            <option value="{{ $tech->id }}" {{ $ticket->assigned_to_id == $tech->id ? 'selected' : '' }}>
                                {{ $tech->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
                <a href="{{ route('tickets.show', $ticket) }}" class="bg-gray-300 px-4 py-2 rounded">Cancel</a>
            </div>
        </form>
    </div>
@endsection
