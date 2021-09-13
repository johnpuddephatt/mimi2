<?php

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Route;
use Spatie\Activitylog\Models\Activity;
use Alive2tinker\ResourceActivities\Resources\ActivityResource;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. You're free to add
| as many additional routes to this file as your tool may require.
|
*/

Route::get('/{resourceName}/{resourceId}', function (Request $request, $resourceName, $resourceId) {
    $logs = Activity::where([
        ['causer_id', $resourceId],
        ['causer_type', "App\Models\\". ucfirst(substr_replace($resourceName, "", -1))]
    ])->get();

    return ActivityResource::collection($logs);
});
