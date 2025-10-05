@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Create Ticket</h1>

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('tickets.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block mb-2">Affected User *</label>
                <select name="affected_user_id" required class="w-full border rounded px-3 py-2">
                    <option value="">Select user</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Problem Description *</label>
                <textarea name="problem_description" required rows="4" class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Received Date *</label>
                <input type="date" name="received_date" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block mb-2">Additional Notes (max 128)</label>
                <input type="text" name="additional_notes" maxlength="128" class="w-full border rounded px-3 py-2">
            </div>

            @if(auth()->user()->isAdmin())
                <div class="mb-4">
                    <label class="block mb-2">Assign To</label>
                    <select name="assigned_to_id" class="w-full border rounded px-3 py-2">
                        <option value="">Unassigned</option>
                        @foreach($technicians as $tech)
                            <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
                <a href="{{ route('tickets.index') }}" class="bg-gray-300 px-4 py-2 rounded">Cancel</a>
            </div>
        </form>
    </div>
@endsection
