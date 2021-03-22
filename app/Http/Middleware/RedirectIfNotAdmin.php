<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAdmin
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
      if(!Auth::User() || !Auth::User()->is_admin) {
        if ($request->expectsJson()) {
          abort(
            response()->json(['message' => 'You don’t have permission to do that'], 403)
          );
        }
        else {
          return redirect(RouteServiceProvider::HOME);
        }
      }

      return $next($request);
    }
}
