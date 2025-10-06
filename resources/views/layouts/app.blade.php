<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketing System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
@auth
    <nav class="bg-white shadow mb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex space-x-8 items-center">
                    <span class="font-bold text-xl">Ticketing System</span>
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                    <a href="{{ route('tickets.index') }}" class="text-gray-700 hover:text-gray-900">Tickets</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('activity-logs.index') }}" class="text-gray-700 hover:text-gray-900">Activity Log</a>
                        <a href="{{ route('users.index') }}" class="text-gray-700 hover:text-gray-900">Users</a>
                    @endif
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">{{ auth()->user()->name }} ({{ auth()->user()->role->name }})</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
@endauth

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    @yield('content')
</div>
</body>
</html>
