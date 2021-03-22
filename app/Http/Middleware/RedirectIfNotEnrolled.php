<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotEnrolled
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
      if(
        Auth::User()->is_admin
        || ($request->route('course') && Auth::User()->courses()->find($request->route('course')->id))
        || ($request->route('lesson') && Auth::User()->courses()->find($request->route('lesson')->week->course->id))
      ){
        return $next($request);
      }

      if ($request->expectsJson()) {
        abort(
          response()->json(['message' => 'You’re not enrolled on this course'], 403)
        );
      }
      else {
        return redirect(RouteServiceProvider::HOME);
      }
    }
}
