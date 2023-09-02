<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DoctorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info('inside doctor');
        Log::info(Auth::check());
        Log::info(Auth::user());
        Log::info(auth()->user());
        if (Auth::check() && Auth::user()->role == 'doctor')  {
            return $next($request);
          } else{
            Auth::logout();
            return redirect()->route('login');
          }
    }
}
