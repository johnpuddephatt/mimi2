<?php
use Illuminate\Http\Request;
use Inertia\Inertia;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::post('log', function (\Illuminate\Http\Request $request) {
  Log::channel('frontend')->info($request->error . ', user: ' . (Auth::user() ? (Auth::user()->first_name . ' ' . Auth::user()->last_name . ' (' . Auth::user()->id . ')'): null ));
});


Route::get('/scheduler', function(){
  return view('scheduler');
})->name('scheduler')->middleware('auth');

Route::get('/', function(){
  return Inertia::render('Home');
})->name('home')->middleware('auth');



Route::get('billing', 'BillingController@listProducts')->name('billing.list-products');
Route::get('billing/{payment_type}/{stripe_price_code}', 'BillingController@paymentForm')->name('billing.payment-form');
Route::post('billing/{payment_type}/{stripe_price_code}/user/create', 'BillingController@createUser')->name('billing.create-user');
Route::post('billing/{payment_type}/{stripe_price_code}/user/{user_hash}', 'BillingController@processPayment')->name('billing.process-payment');

// Route::get('billing/single/add-payment-method/{stripe_price_code}/', 'BillingController@addSinglePaymentMethod')->name('billing.add-single-payment-method');
// Route::post('billing/single/create/{stripe_price_code}', 'BillingController@createUserPayment')->name('billing.create-user-payment');

Route::get('billing/portal', 'BillingController@billingPortal')->name('billing.portal')->middleware('auth');



Route::get('stats', 'StatController@index')->name('stats');
Route::get('stats/teacher-monthly/{from}/{to}', 'StatController@teacherMonthly')->name('stats.teacherMonthly')->middleware('admin');
Route::get('stats/teacher-weekly/{date}', 'StatController@teacherWeekly')->name('stats.teacherWeekly')->middleware('admin');
Route::get('stats/replies-monthly/{from}/{to}', 'StatController@repliesMonthly')->name('stats.repliesMonthly')->middleware('admin');


Route::get('section/{section}', 'SectionController@single')->name('section.single')->middleware('auth');

Route::get('users', 'UserController@index')->name('users.index')->middleware('admin');

Route::get('courses', 'CourseController@index')->name('course.index')->middleware('auth');

Route::get('course/{course}/enroll', 'CourseController@enrollCurrentUser')->name('course.enrollCurrentUser');
Route::post('course/{course}/enroll', 'CourseController@enroll')->name('course.enroll')->middleware('admin');
Route::get('course/{course}/unenroll/user/{user}', 'CourseController@unenroll')->name('course.unenroll')->middleware('admin');

Route::get('course/{course}', 'CourseController@show')->name('course.show')->middleware('auth','enrolled');
Route::get('course/{course}/users', 'CourseController@users')->name('course.users')->middleware('admin');
Route::get('course/{course}/map', 'CourseController@map')->name('course.map')->middleware('auth', 'enrolled');

Route::get('course/{course}/week/{week:number}', 'WeekController@show')->name('week.show')->middleware('auth','enrolled');


Route::get('course/{course}/week/{week:number}/lesson/{lesson}', 'LessonController@show')->name('lesson.show')->middleware('auth','enrolled');

Route::post('lesson/{lesson}/reply', 'ReplyController@store')->name('reply.create')->middleware('auth','enrolled');
Route::delete('lesson/{lesson}/reply/{reply}/delete', 'ReplyController@destroy')->name('reply.delete')->middleware('auth','owner');

Route::post('course/{course}/week/{week:number}/lesson/{lesson}/section/{section}/reply/{reply}/comment', 'CommentController@store')->name('comment.create')->middleware('auth','enrolled');
Route::get('lesson/{lesson}/reply/{reply}/comments', 'CommentController@index')->name('comment.index')->middleware('auth','enrolled');
Route::delete('course/{course}/week/{week:number}/lesson/{lesson}/section/{section}/reply/{reply}/comment/{comment}/delete', 'CommentController@delete')->name('comment.delete')->middleware('auth','enrolled');

Route::get('course/{course}/week/{week:number}/lesson/{lesson}/section/{section}', 'SectionController@show')->name('section.show')->middleware('auth','enrolled');
Route::get('course/{course}/week/{week:number}/lesson/{lesson}/section/{section}/reply/{reply}/{show_feedback?}', 'SectionController@show')->name('section.reply')->middleware('auth','enrolled');
Route::get('course/{course}/week/{week:number}/lesson/{lesson}/section/{section}/delete', 'SectionController@delete')->name('section.delete')->middleware('auth','enrolled');


Route::get('admin/courses', 'CourseController@manage')->name('courses.manage')->middleware('admin');
Route::get('admin/course/new', 'CourseController@create')->name('course.new')->middleware('admin');
Route::post('admin/course/create', 'CourseController@store')->name('course.create')->middleware('admin');
Route::get('admin/course/{course}', 'CourseController@edit')->name('course.edit')->middleware('admin');
Route::put('admin/course/{course}', 'CourseController@update')->name('course.update')->middleware('admin');
Route::delete('admin/course/{course}', 'CourseController@delete')->name('course.delete')->middleware('admin');

Route::get('admin/course/{course}/week/new', 'WeekController@create')->name('week.create')->middleware('admin');
Route::post('admin/course/{course}/week/create', 'WeekController@store')->name('week.store')->middleware('admin');
Route::put('admin/course/{course}/week/{week:number}', 'WeekController@update')->name('week.update')->middleware('admin');
Route::get('admin/course/{course}/week/{week:number}', 'WeekController@edit')->name('week.edit')->middleware('admin');
Route::delete('admin/course/{course}/week/{week:number}', 'WeekController@destroy')->name('week.delete')->middleware('admin');

Route::get('admin/course/{course}/week/{week:number}/lesson/new', 'LessonController@create')->name('lesson.create')->middleware('admin');
Route::post('admin/course/{course}/week/{week:number}/lesson/create', 'LessonController@store')->name('lesson.store')->middleware('admin');
Route::get('admin/course/{course}/week/{week:number}/lesson/{lesson}', 'LessonController@edit')->name('lesson.edit')->middleware('admin');
Route::put('admin/course/{course}/week/{week:number}/lesson/{lesson}', 'LessonController@update')->name('lesson.update')->middleware('admin');
Route::delete('admin/course/{course}/week/{week:number}/lesson/{lesson}', 'LessonController@destroy')->name('lesson.delete')->middleware('admin');
Route::post('admin/course/{course}/week/{week:number}/lesson/{lesson}/reordersections/{newOrder}', 'LessonController@reorderSections')->name('lesson.reorderSections')->middleware('admin');

Route::get('admin/course/{course}/week/{week:number}/lesson/{lesson}/section/new', [App\Http\Controllers\SectionController::class, 'create'])->name('section.create')->middleware('admin');
Route::post('admin/course/{course}/week/{week:number}/lesson/{lesson}/section/create', 'SectionController@store')->name('section.store')->middleware('admin');
Route::put('admin/course/{course}/week/{week:number}/lesson/{lesson}/section/{section}', 'SectionController@update')->name('section.update')->middleware('admin');
Route::get('admin/course/{course}/week/{week:number}/lesson/{lesson}/section/{section}', 'SectionController@edit')->name('section.edit')->middleware('admin');
Route::delete('admin/course/{course}/week/{week:number}/lesson/{lesson}/section/{section}', 'SectionController@delete')->name('section.delete')->middleware('admin');

Route::post('admin/upload/store', 'UploadController@store')->name('upload.store')->middleware('admin');


Route::get('admin/activity', 'ActivityController@index')->name('activities')->middleware('admin');

Route::get('admin/emails', 'AdminController@emails')->name('admin.emails')->middleware('admin');

Route::get('admin/emails/newreply', function () {
    $reply = App\Models\Reply::whereNull('reply_id')->latest()->first();
    abort_if(!$reply, 404);
    return new App\Mail\NewReply($reply);
})->name('admin.emails.newreply')->middleware('admin');

Route::get('admin/emails/newreplyfeedback', function () {
    $reply = App\Models\Reply::whereNotNull('reply_id')->latest()->first();
    abort_if(!$reply, 404);
    return new App\Mail\NewFeedback($reply);
})->name('admin.emails.newfeedback')->middleware('admin');;

Route::get('admin/emails/newcomment', function () {
    $comment = App\Models\Comment::latest()->first();
    abort_if(!$comment, 404);
    return new App\Mail\NewComment($comment);
})->name('admin.emails.newcomment')->middleware('admin');;
