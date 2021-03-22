<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      Validator::extend('course', function ($attribute, $value, $parameters, $validator) {
        return \App\Models\Course::find(\Hashids::decode($value))->count();
      }, 'Specified course could not be found');

      if(isset($_GET['debug'])) {
        \Debugbar::enable();
       }

    }
}
