<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StoreCohort;
use App\Providers\RouteServiceProvider;

class CohortController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(\Auth::User()->is_admin) {
        $cohorts = Cohort::active()->get();
        $inactive_cohorts = Cohort::withoutGlobalScopes()->inactive()->get();
      }
      else {
        $cohorts = \Auth::User()->cohorts()->active()->get();
        
        if(\Auth::User()->subscribed() || \Auth::User()->hasActiveCohort() || \Auth::User()->hasCredits()) {
            $cohorts = $cohorts->merge(Cohort::companion()->get());
        }
        $inactive_cohorts = \Auth::User()->inactiveCohorts()->get();
      }
      return Inertia::render('Cohort/Index', compact('cohorts','inactive_cohorts'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Admin/Cohort/Form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCohort $request, Course $course)
    {
        $cohort = Cohort::create(array_merge($request->all(), [
            'course_id' => $course->id
        ]));
        return redirect()->route('cohort.edit', ['course' => $course, 'cohort' => $cohort]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    public function show(Cohort $cohort, Course $course)
    {
      // if(!$cohort->active && !\Auth::user()->is_admin) {
      //   return back()->with('message', 'This class is not live yet.');
      // }
      $course->load(['weeks' => function ($query) {
        $query->where('live', true);
      }]);
      return Inertia::render('Cohort/Show', ['course' => $course]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course, Cohort $cohort)
    {
      return Inertia::render('Admin/Cohort/Form', ['data' => $cohort ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course, Cohort $cohort)
    {
        $cohort->update($request->all());
        return redirect()->route('cohort.edit', ['cohort' => $cohort, 'course' => $course]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cohort $cohort)
    {
        //
    }

    public function enrollCurrentUser($cohortHash) {
      $cohort_id = \Hashids::decode($cohortHash)[0];
      $cohort = Cohort::find($cohort_id);

      if (\Auth::guard()->check()) {
        if(!$cohort) {
          return redirect(RouteServiceProvider::HOME)->with('message', 'Invalid registration link');
        }

        if(\Auth::User()->cohorts->contains($cohort_id)) {
          return redirect(RouteServiceProvider::HOME)->with('message', 'You are already enrolled in this class');
        }
        else {
          \Auth::User()->cohorts()->attach($cohort_id, [
            'is_subscription_based' => ((\Auth::User()->subscribed() && !$cohort->companion) ? true : false)
          ]);
          if(!$cohort->companion && \Auth::User()->hasCredits()) {
            \Auth::User()->decrement('credits');
          }
          return redirect(RouteServiceProvider::HOME)->with('message', 'You have been enrolled.');

          // if(\Auth::User()->subscribed() || \Auth::User()->hasCredits() || $cohort->companion) {
          //   \Auth::User()->cohorts()->attach($cohort_id, [
          //     'is_subscription_based' => ((\Auth::User()->hasCredits() || $cohort->companion) ? false : true)
          //   ]);
          //   if(!$cohort->companion && \Auth::User()->hasCredits()) {
          //     \Auth::User()->decrement('credits');
          //   }
          //   return redirect(RouteServiceProvider::HOME)->with('message', 'You have been enrolled.');
          // }
          // else {
          //   return redirect(RouteServiceProvider::HOME)->with('message', 'You do not have a active payment plan.');
          // }

        }
      }
      // The below allows unregistered users to create an account to enrol.
      else {
        return view('enrol', compact('cohortHash'));
      }

      // else {  
      //   return redirect()->route('login', ['cohort' => $cohort ]);
      // }
    }

    public function enroll(Request $request, Cohort $cohort) {
      $user_ids = $request->all();

      foreach($user_ids as $user_id) {
        $user = User::find($user_id);
        if(!$user->cohorts->contains($cohort)) {
          $user->cohorts()->attach($cohort, [
            'is_subscription_based' => (($user->subscribed() && !$cohort->companion) ? true : false)
          ]);
          if(!$cohort->companion && $user->hasCredits()) {
            $user->decrement('credits');
          }
        }
      }
    }

    public function unenroll(Cohort $cohort, User $user) {
      $user->cohorts()->detach($cohort->id);
      return back();
    }

    public function users(Cohort $cohort) {
      return $cohort->users;
    }
}
