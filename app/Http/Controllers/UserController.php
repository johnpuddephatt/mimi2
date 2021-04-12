<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Http\Requests\StoreCourse;
use App\Providers\RouteServiceProvider;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
      return User::select('id', 'first_name', 'last_name', 'email')->where('is_admin', false)->orderBy('last_name')->get();
    }
}
