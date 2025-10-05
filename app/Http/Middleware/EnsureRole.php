<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!$request->user() || !in_array($request->user()->role->name, $roles)) {
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
