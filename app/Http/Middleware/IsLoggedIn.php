<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsLoggedIn
{
    public function handle($request, Closure $next)
    {
        if(auth()->guard('clients')->check()){
            return $next($request);
        }
        else
            return redirect()->route('login');
    }
}
