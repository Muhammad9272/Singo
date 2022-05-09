<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
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
        if(auth()->user()->type !=0 || str_contains(\Route::getFacadeRoot()->current()->uri(), 'cancel_stripe')) {
            return $next($request);
        }
        return redirect()->route('home');
    }
}
