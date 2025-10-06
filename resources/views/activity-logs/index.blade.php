@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Activity Log</h1>

        <div class="space-y-3">
            @php
                $logs = \App\Models\ActivityLog::with('user')
                    ->latest()
                    ->limit(50)
                    ->get();
            @endphp

            @forelse($logs as $log)
                <div class="flex items-start space-x-4 p-3 bg-gray-50 rounded">
                    <div class="flex-shrink-0">
                <span class="inline-block w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-semibold">
                    {{ $log->user ? strtoupper(substr($log->user->name, 0, 1)) : '?' }}
                </span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm">{{ $log->description }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $log->created_at->diffForHumans() }}
                            <span class="mx-2">•</span>
                            {{ $log->created_at->format('Y-m-d H:i:s') }}
                        </p>
                    </div>
                    <div>
                <span class="px-2 py-1 text-xs rounded
                    {{ $log->action === 'created' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $log->action === 'deleted' ? 'bg-red-100 text-red-800' : '' }}
                    {{ $log->action === 'restored' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $log->action === 'assigned' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $log->action === 'commented' ? 'bg-purple-100 text-purple-800' : '' }}">
                    {{ ucfirst($log->action) }}
                </span>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No activity logged yet</p>
            @endforelse
        </div>
    </div>
@endsection
