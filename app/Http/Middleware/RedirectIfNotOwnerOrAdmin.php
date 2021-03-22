<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotOwnerOrAdmin
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
      if(!(Auth::User()->is_admin) &&  !(Auth::User()->id == $request->route('reply')->user->id)) {
        if ($request->expectsJson()) {
          abort(
            response()->json(['message' => 'You don’t have permission to do that'], 403)
          );
        }
        else {
          return redirect()->back();
        }
      }

      return $next($request);
    }
}
