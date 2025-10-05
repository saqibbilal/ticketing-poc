@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Welcome to Ticketing System</h1>
        <p class="text-gray-600">You are logged in as: <strong>{{ auth()->user()->name }}</strong></p>
        <p class="text-gray-600">Role: <strong>{{ auth()->user()->role->name }}</strong></p>
        <p class="text-sm text-gray-500 mt-4">More features coming soon...</p>
    </div>
@endsection
