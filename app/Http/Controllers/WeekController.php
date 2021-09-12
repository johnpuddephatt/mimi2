<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Redirect;
use App\Models\Week;
use App\Models\Course;
use App\Models\Cohort;
use Illuminate\Http\Request;
use App\Http\Requests\StoreWeek;

class WeekController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return Inertia::render('Admin/Week/Form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWeek $request, Course $course)
    {

      $week = Week::create(array_merge($request->validated(), [
        'course_id' => $course->id,
      ]));

      return Redirect::route('course.edit', [
        'course' => $course
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Week  $week
     * @return \Illuminate\Http\Response
     */
    public function show(Cohort $cohort, Course $course, Week $week)
    {
      $week->load('lessons');
      return Inertia::render('Week/Show', ['course' => $course, 'week' => $week]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Week  $week
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course, Week $week)
    {
      return Inertia::render('Admin/Week/Form', ['data' => $week]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Week  $week
     * @return \Illuminate\Http\Response
     */
    public function update(StoreWeek $request, Course $course, Week $week)
    {
      $week->update($request->validated());

      // $week = $week->update([
      //   'name' => $request->name,
      //   'description' => $request->description,
      //   'number' => intval($request->number),
      //   'course_id' => $course->id,
      // ]);

      return Redirect::route('course.edit', [
        'course' => $course
      ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Week  $week
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Course $course, Week $week)
    {
      $week->delete();

      return Redirect::route('course.edit', [
        'course' => $course
      ]);
    }
}
