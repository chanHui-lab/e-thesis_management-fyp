<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// testing de page
Route::get('/testing', function(){
    // return view ('admin.adminpage.create');
    return view ('test');

});

Route::get('test', function(){
    // return view ('admin.adminpage.create');
    return view ('admin.presentationSchedule');

});

// Route::get('/calendar', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'getEvents']);
// Route::get('/calendar/events/{date}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'getDateEvents']);
// Route::get('/calendar/{eventId}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'getEventDetails']);
// Route::post('/calendar/update/{eventId}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'updateEvent']);
// // Route::put('/calendar/update/{eventId}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'updateEvent']);
// // Route::get('/fetch-updated-events', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'fetchUpdatedEventSource']);
// Route::post('/calendar/store', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'store'])->name('calendar.store');
// // Route::get('/calendar/events-by-date', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'getEventsByDate']);
// Route::post('/calendar/updateEvent/{id}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'changeEvent']);

// must add a auth there!!
Route::group(['middleware' => 'auth','isAdmin', 'prefix' => 'admin'], function(){

    // to get the admin dashboard
    Route::get('dashboard',[App\Http\Controllers\Admin\DashboardController::class, 'index']) -> name('admin_dashboard');

    Route::get('adminpage/adminallthesissubmission',[App\Http\Controllers\Admin\FormController::class, 'test2']);

    Route::get('thesispage/thesistemplateupload',[App\Http\Controllers\Admin\FormController::class, 'testthesis']);

    Route::get('formpage/admintemplateupload',[App\Http\Controllers\Admin\FormController::class, 'index']) -> name('template.index');
    Route::get('formpage/admintemplateupload/create',[App\Http\Controllers\Admin\FormController::class, 'create']) -> name('template.create');
    Route::post('formpage/admintemplateupload/create',[App\Http\Controllers\Admin\FormController::class, 'store']) -> name('template.store');
    Route::get('formpage/admintemplateupload/{id}',[App\Http\Controllers\Admin\FormController::class, 'show']) -> name('template.show');
    Route::get('formpage/admintemplateupload/edit/{id}',[App\Http\Controllers\Admin\FormController::class, 'edit']) -> name('template.edit');
    Route::post('formpage/admintemplateupload/edit/{id}',[App\Http\Controllers\Admin\FormController::class, 'update']) -> name('template.updatew');
    Route::delete('formpage/admintemplateupload/delete/{id}',[App\Http\Controllers\Admin\FormController::class, 'destroy']) -> name('template.destroy');

    // form submission post
    Route::get('formsubmissionpage/allpost',[App\Http\Controllers\Admin\FormController::class, 'indexPost']) -> name('formpost.index');
    Route::get('formsubmissionpage/create',[App\Http\Controllers\Admin\FormController::class, 'createPost']) -> name('formpost.create');
    Route::post('formsubmissionpage/create',[App\Http\Controllers\Admin\FormController::class, 'storePost']) -> name('formpost.store');
    Route::delete('formsubmissionpage/delete/{id}',[App\Http\Controllers\Admin\FormController::class, 'destroyPost']) -> name('formpost.destroy');
    Route::get('formsubmissionpage/edit/{id}',[App\Http\Controllers\Admin\FormController::class, 'editPost']) -> name('formpost.edit');
    Route::put('formsubmissionpage/update/{id}',[App\Http\Controllers\Admin\FormController::class, 'updatePost']) -> name('formpost.update');
    Route::delete('formsubmissionpage/remove/{id}',[App\Http\Controllers\Admin\FormController::class, 'removeFile']) -> name('formpost.deletefile');

    //view form submission post for each student
    Route::get('formsubmissionpage/viewAll/{submissionPostId}', [App\Http\Controllers\Admin\FormController::class, 'getFormSubmissionForLecturer'])->name('formpost.showAll');
    // Route::get('formsubmissionpage/viewOne/{submissionPostId}/{formSubmissionId}/{studentId}', [App\Http\Controllers\Admin\FormController::class, 'showFormSubmissions'])->name('formpost.show');
    Route::get('formsubmissionpage/viewOne/{formSubmissionId}', [App\Http\Controllers\Admin\FormController::class, 'showFormSubmissions'])->name('formpost.show');

    Route::get('advisor-assignment/create', [App\Http\Controllers\Admin\SupervisorStudentAssignmentController::class, 'create'])->name('advisor-assignment.create');
    Route::post('advisor-assignment/store', [App\Http\Controllers\Admin\SupervisorStudentAssignmentController::class, 'store'])->name('advisor-assignment.store');


    // for calendar - admin view
    Route::get('/calendar', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'getEvents'])->name('calendar.index');;
    Route::get('/calendar/events/{date}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'getDateEvents']);
    Route::get('/calendar/{eventId}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'getEventDetails']);
    Route::post('/calendar/update/{eventId}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'updateEvent']);
    Route::post('/calendar/store', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'store'])->name('calendar.store');
    Route::post('/calendar/updateEvent/{id}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'changeEvent']);

    Route::post('calendar/uploadSche', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'uploadPresentationSche'])->name('calendarsche.upload');
    // Route::get('calendar/showAllFile',[App\Http\Controllers\Admin\FormController::class, 'showAllFile']) -> name('calendarsche.showfile');
    Route::get('calendar/ii',[App\Http\Controllers\Admin\PresentationScheduleController::class, 'showAllFile']) -> name('calendarsche.showfile');
    // Route::get('calendar/download/{id}',[App\Http\Controllers\Admin\FormController::class, 'showAllFile']) -> name('calendarsche.showfile');
    Route::get('/download/template/{id}', [App\Http\Controllers\Admin\PresentationScheduleController::class,'downloadTemplate'])->name('download.template');
    // web.php or routes file
    Route::get('/delete/template/{id}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'deleteTemplate'])->name('delete.template');

    Route::delete('/calendar/delete-event/{eventId}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'deleteEvent']);

// routes/web.php
// Route::get('/templates/download/{template}', 'TemplateController@download')->name('templates.download');

});
// Route::delete('/admin/formsubmissionpage/{id}/deletefile/{file}', 'FormController@deleteFile')->name('formpost.deletefile');

Route::group(['middleware' => 'auth','isStudent', 'prefix' => 'student'], function(){


    Route::get('dashboard',[App\Http\Controllers\Admin\DashboardController::class, 'index']) -> name('student_dashboard');
    Route::get('student/studentlist',[App\Http\Controllers\Admin\DashboardController::class, 'test']);
});

// Route::get('/admin/dashboard');

// Route::get('/admin/dashboard',function(){
//     return view('welcome');
// })->where('pathMatch',".*");


// Route::get('{view}', ApplicationController::class)-> where('view','(*)');