<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \App\Model\User;

class CanAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!User::canView('admin') && !User::canAdmin('admin')) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
