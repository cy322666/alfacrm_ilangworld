<?php

namespace App\Http\Middleware;

use Closure;

class UserChangeMiddleware
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
