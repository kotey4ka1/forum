<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminOrModerator
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isModerator())) {
            return $next($request);
        }
        abort(403, 'Доступ запрещён. Только для администраторов и модераторов.');
    }
}
