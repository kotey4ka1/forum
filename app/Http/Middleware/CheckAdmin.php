<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role && auth()->user()->role->name === 'admin') {
            return $next($request);
        }
        abort(403, 'Доступ запрещён. Только для администраторов.');
    }
}
