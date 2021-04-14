<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\StoreUser;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

use Redirect;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(Request $request)
     {
        if($request->query('admin') == config('app.admin_invite_key')) {
          return Inertia::render('Registration', ['admin' => $request->query('admin')]);
        }
        else {
          return Inertia::render('Registration');
        }

     }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'photo' => ['required', 'image'],
            'description' => ['required','string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'course' => ['nullable'],
            'admin' => ['nullable']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data) {

        $resized = \Image::make($data['photo'])->orientate()->fit(480,480)->encode('jpg',80);
        $photo_path = \App\Models\User::$photo_directory . $data['photo']->hashName();
        Storage::cloud()->put($photo_path, $resized);

        $user =  User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'photo' => Storage::cloud()->url($photo_path),
            'description' => $data['description'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => isset($data['admin']) && ($data['admin'] == config('app.admin_invite_key'))
        ]);

        return $user;
    }
}
