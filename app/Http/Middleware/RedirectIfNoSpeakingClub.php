<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RedirectIfNoSpeakingClub
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

      // Return next if user is an admin
      if(Auth::User()->is_admin || Auth::User()->hasAccessToSpeakingClub()) {
        return $next($request);
      }
      
    elseif ($request->expectsJson()) {
        
        abort(
          response()->json(['message' => 'You’re not enrolled in a class that includes speaking club access.'], 403)
        );
      }

      else {

        return redirect(RouteServiceProvider::HOME)->with('message', 'You’re not enrolled in a class that includes speaking club access.');
      }
    }
}
