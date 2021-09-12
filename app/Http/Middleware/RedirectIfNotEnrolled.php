<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
      if($request->route('cohort')) {
        $cohort_id = $request->route('cohort')->id;
      }

      // Return next if user is an admin
      if(Auth::User()->is_admin) {
        return $next($request);
      }
      
      // Return next if we’re not looking at a cohort
      if(!$cohort_id) {
        return $next($request);
      }

      // TEMPORARY: if cohort is inactive (i.e. old) allow access regardless... allows for lifelong members to access
      if(!$request->route('cohort')->active) {
        return $next($request);
      }

      // Return next if cohort is companion and user is enrolled in an active non-companion cohort
      if($request->route('cohort')->companion && (Auth::User()->hasActiveCohort() || Auth::User()->subscribed())) {
        return $next($request);
      }

      // Return next if user is enrolled on cohort and either has subscription OR the enrolment wasn’t subscription based
      if(Auth::User()->cohorts()->find($cohort_id) && (Auth::User()->subscribed() || !Auth::User()->cohorts()->find($cohort_id)->pivot->is_subscription_based)) {
        return $next($request);
      }

    if($request->hasHeader('X-Inertia')) {
      
      return \Inertia\Inertia::render('Error', ['status' => '403']);
            
    }

    elseif ($request->expectsJson()) {
        
        abort(
          response()->json(['message' => 'You’re not enrolled on this class'], 403)
        );
      }

      else {

        return redirect(RouteServiceProvider::HOME)->with('message', 'You’re not enrolled on this class or your subscription has expired.');
      }
    }
}
