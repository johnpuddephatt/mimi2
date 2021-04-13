<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAlreadyPaying
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
      if(Auth::User() && (Auth::User()->subscribed() || Auth::User()->hasCredits())) {
        if ($request->expectsJson()) {
          abort(
            response()->json(['message' => 'You already have an active payment plan'], 403)
          );
        }
        else {
          return redirect(RouteServiceProvider::HOME)->with('message', 'You already have an active payment plan');
        }
      }

      return $next($request);
    }
}
