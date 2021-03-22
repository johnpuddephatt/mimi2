<?php

namespace App\Http\Controllers;
use Inertia\Inertia;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reply;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class StatController extends Controller
{
  public function index()
  {
    return Inertia::render('Admin/Stats');
  }

  public function repliesMonthly($from, $to) {
    $replies_by_month = Reply::select('created_at')->where('reply_id', null)->whereBetween('created_at',[date($from),date($to)])
            ->get()
            ->groupBy(function($val) {
              return Carbon::parse($val->created_at)->format('M');
            });

    $counts = [];

    foreach($replies_by_month as $month => $replies) {
      $counts[$month] = $replies->count();
    }

    return $counts;
  }

  public function teacherMonthly($from, $to) {
    return User::select('first_name', 'last_name')->where('is_admin',true)->withCount(['replies' => function (Builder $query) use ($from, $to) {
      $query->whereBetween('created_at',[date($from),date($to)]);
    }])->get();
  }

  public function teacherWeekly($date) {
    return User::select('first_name', 'last_name')->where('is_admin',true)->withCount(['replies' => function (Builder $query) use ($date) {
      $query->whereBetween('created_at',[date($date),Carbon::parse($date)->add('week',1)]);
    }])->get();
  }
}
