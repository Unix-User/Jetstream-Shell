<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckGroupId
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->current_team_id !== 1) {
            return redirect('dashboard');

        }
        return $next($request);
    }
}