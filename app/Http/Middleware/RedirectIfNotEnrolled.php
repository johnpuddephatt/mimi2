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
      if($request->route('course')) {
        $course_id = $request->route('course')->id;
      }
      else if($request->route('lesson')) {
        $course_id = $request->route('lesson')->week->course->id;
      }

      // Return next if user is an admin or we’re not looking at a course or course is open access
      if(!$course_id || Auth::User()->is_admin || $course->is_open){
        return $next($request);
      }

      // Return next if user is enrolled on course and either has subscription OR the enrolment isn’t subscription based
      if(Auth::User()->courses()->find($course_id) && (Auth::User()->subscribed() || !Auth::User()->courses()->find($course_id)->pivot->is_subscription_based)) {
        return $next($request);
      }

      if ($request->expectsJson()) {
        abort(
          response()->json(['message' => 'You’re not enrolled on this course'], 403)
        );
      }
      else {
        return redirect(RouteServiceProvider::HOME)->with('message', 'You’re not enrolled on this course or your subscription has expired.');
      }
    }
}
