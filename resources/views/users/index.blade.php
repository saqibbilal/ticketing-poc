@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Users</h1>
            <a href="{{ route('users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Create User</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <table class="w-full">
            <thead>
            <tr class="bg-gray-50">
                <th class="px-4 py-2 text-left">ID</th>
                <th class="px-4 py-2 text-left">Name</th>
                <th class="px-4 py-2 text-left">Email</th>
                <th class="px-4 py-2 text-left">Role</th>
                <th class="px-4 py-2 text-left">Created</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $user->id }}</td>
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2">
                    <span class="px-2 py-1 text-xs rounded
                        {{ $user->role->name === 'Admin' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $user->role->name === 'Technician' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $user->role->name === 'Regular' ? 'bg-gray-100 text-gray-800' : '' }}">
                        {{ $user->role->name }}
                    </span>
                    </td>
                    <td class="px-4 py-2">{{ $user->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
