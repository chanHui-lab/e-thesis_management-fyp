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
    return view('auth.login');
});


Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// testing de page
Route::get('/testing', function(){
    // return view ('admin.adminpage.create');
    return view ('admin.online_eva_form');

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
Route::group(['middleware' => ['auth', 'isAdmin'], 'prefix' => 'admin'], function () {

    Route::get('/notifications', [App\Http\Controllers\Admin\NotiController::class, 'index']);

    // to get the admin dashboard
    Route::get('dashboard',[App\Http\Controllers\Admin\DashboardController::class, 'index']) -> name('admin_dashboard');

    // Route::get('adminpage/adminallthesissubmission',[App\Http\Controllers\Admin\FormController::class, 'test2']);

    // Route::get('thesispage/thesistemplateupload',[App\Http\Controllers\Admin\FormController::class, 'testthesis']);

    Route::get('formpage/admintemplateupload',[App\Http\Controllers\Admin\FormController::class, 'index']) -> name('formtemplate.index');
    Route::get('formpage/admintemplateupload/create',[App\Http\Controllers\Admin\FormController::class, 'create']) -> name('template.create');
    // Unified for all type templates
    Route::post('formpage/admintemplateupload/create',[App\Http\Controllers\Admin\FormController::class, 'store']) -> name('template.store');

    Route::get('formpage/admintemplateupload/{id}',[App\Http\Controllers\Admin\FormController::class, 'show']) -> name('template.show');
    Route::get('formpage/admintemplateupload/edit/{id}',[App\Http\Controllers\Admin\FormController::class, 'edit']) -> name('template.edit');
    Route::put('formpage/admintemplateupload/edit/{id}',[App\Http\Controllers\Admin\FormController::class, 'update']) -> name('template.updatew');
    Route::delete('formpage/admintemplateupload/delete/{id}',[App\Http\Controllers\Admin\FormController::class, 'destroy']) -> name('template.destroy');
    Route::delete('formpage/remove-file/{id}',  [App\Http\Controllers\Admin\FormController::class, 'removeTemplateFile'])->name('template.remove-file');

    // form submission post
    Route::get('formsubmissionpage/allpost',[App\Http\Controllers\Admin\FormController::class, 'indexPost']) -> name('formpost.index');
    Route::get('formsubmissionpage/create',[App\Http\Controllers\Admin\FormController::class, 'createPost']) -> name('formpost.create');
    Route::post('formsubmissionpage/create',[App\Http\Controllers\Admin\FormController::class, 'storePost']) -> name('formpost.store');
    Route::delete('formsubmissionpage/delete/{id}',[App\Http\Controllers\Admin\FormController::class, 'destroyPost']) -> name('formpost.destroy');
    Route::get('formsubmissionpage/edit/{id}',[App\Http\Controllers\Admin\FormController::class, 'editPost']) -> name('formpost.edit');
    Route::put('formsubmissionpage/update/{id}',[App\Http\Controllers\Admin\FormController::class, 'updatePost']) -> name('formpost.update');
    // Route::post('formsubmissionpage/remove',[App\Http\Controllers\Admin\FormController::class, 'removeFile']) -> name('formpost.deletefile');
    Route::delete('formsubmissionpage/remove-file/{submissionPostId}',  [App\Http\Controllers\Admin\FormController::class, 'removeFile'])->name('formpost.remove-file');

    //view form submission post for each student
    Route::get('formsubmissionpage/viewAll/{submissionPostId}', [App\Http\Controllers\Admin\FormController::class, 'getFormSubmissionForLecturer'])->name('formpost.showAll');
    // Route::get('formsubmissionpage/viewOne/{submissionPostId}/{formSubmissionId}/{studentId}', [App\Http\Controllers\Admin\FormController::class, 'showFormSubmissions'])->name('formpost.show');
    Route::get('formsubmissionpage/viewOne/{formSubmissionId}', [App\Http\Controllers\Admin\FormController::class, 'showFormSubmissions'])->name('formpost.show');
    Route::post('formsubmissionpage/viewAll/download-all-files', [App\Http\Controllers\Admin\FormController::class, 'downloadAllFiles']);

    // for comments
    Route::post('formsubmissionpage/viewOne/{formSubmissionId}/addComment', [App\Http\Controllers\CommentController::class, 'addCommentToFormSubmission'])->name('formpostShow.addComment');
    Route::get('formsubmissionpage/viewOne/comment/delete/{commentId}', [App\Http\Controllers\CommentController::class, 'deleteLectComment'])->name('lectFormSubmission.deletecomment');

    // THESIS
    Route::get('thesispage/admintemplatelist',[App\Http\Controllers\Admin\ThesisController::class, 'index']) -> name('thesistemplate.index');
    Route::get('thesispage/admintemplatelist/create',[App\Http\Controllers\Admin\ThesisController::class, 'create']) -> name('thesistemplate.create');
    Route::get('thesispage/admintemplatelist/edit/{id}',[App\Http\Controllers\Admin\ThesisController::class, 'edit']) -> name('thesistemplate.edit');

    // thesis submission post
    Route::get('thesissubmissionpage/allpost',[App\Http\Controllers\Admin\ThesisController::class, 'indexPost']) -> name('thesispost.index');
    Route::get('thesissubmissionpage/create',[App\Http\Controllers\Admin\ThesisController::class, 'createPost']) -> name('thesispost.create');
    Route::post('thesissubmissionpage/create',[App\Http\Controllers\Admin\ThesisController::class, 'storePost']) -> name('thesispost.store');
    Route::delete('thesissubmissionpage/delete/{id}',[App\Http\Controllers\Admin\ThesisController::class, 'destroyPost']) -> name('thesispost.destroy');
    Route::get('thesissubmissionpage/edit/{id}',[App\Http\Controllers\Admin\ThesisController::class, 'editPost']) -> name('thesispost.edit');
    Route::put('thesissubmissionpage/update/{id}',[App\Http\Controllers\Admin\ThesisController::class, 'updatePost']) -> name('thesispost.update');

    Route::get('thesissubmissionpage/viewAll/{submissionPostId}', [App\Http\Controllers\Admin\ThesisController::class, 'getThesisSubmissionForLecturer'])->name('thesispost.showAll');
    Route::get('thesissubmissionpage/viewOne/{thesisSubmissionId}', [App\Http\Controllers\Admin\ThesisController::class, 'showThesisSubmissions'])->name('thesispost.show');
    // Add a custom route for updating thesis status
    Route::patch('/thesissubmissionpage/{submission}/update-status', [App\Http\Controllers\Admin\ThesisController::class, 'updateStatus'])
        ->name('updateThesisStatus');


    // PROPOSAL SECTION
    Route::get('proposalsubmissionpage/admintemplatelist',[App\Http\Controllers\Admin\ProposalController::class, 'index']) -> name('proptemplate.index');
    Route::get('proposalsubmissionpage/admintemplatelist/create',[App\Http\Controllers\Admin\ProposalController::class, 'create']) -> name('proptemplate.create');
    Route::get('proposalsubmissionpage/admintemplatelist/edit/{id}',[App\Http\Controllers\Admin\ProposalController::class, 'edit']) -> name('proptemplate.edit');

    Route::get('proposalsubmissionpage/allpost',[App\Http\Controllers\Admin\ProposalController::class, 'indexPost']) -> name('proposalpost.index');
    Route::get('proposalsubmissionpage/create',[App\Http\Controllers\Admin\ProposalController::class, 'createPost']) -> name('proposalpost.create');
    Route::post('proposalsubmissionpage/create',[App\Http\Controllers\Admin\ProposalController::class, 'storePost']) -> name('proposalpost.store');
    Route::get('proposalsubmissionpage/edit/{id}',[App\Http\Controllers\Admin\ProposalController::class, 'editPost']) -> name('proposalpost.edit');
    Route::get('proposalsubmissionpage/viewAll/{submissionPostId}', [App\Http\Controllers\Admin\ProposalController::class, 'getProposalSubmissionForLecturer'])->name('proposalpost.showAll');
    Route::get('proposalsubmissionpage/viewOne/{proposalSubmissionId}', [App\Http\Controllers\Admin\ProposalController::class, 'showProposalSubmissions'])->name('proposalpost.show');


    // SLIDES SECTION
    Route::get('slidessubmissionpage/admintemplatelist',[App\Http\Controllers\Admin\SlideController::class, 'index']) -> name('slidestemplate.index');
    Route::get('slidessubmissionpage/admintemplatelist/create',[App\Http\Controllers\Admin\SlideController::class, 'create']) -> name('slidestemplate.create');
    Route::get('slidessubmissionpage/admintemplatelist/edit/{id}',[App\Http\Controllers\Admin\SlideController::class, 'edit']) -> name('slidestemplate.edit');

    Route::get('slidessubmissionpage/allpost',[App\Http\Controllers\Admin\SlideController::class, 'indexPost']) -> name('slidespost.index');
    Route::get('slidessubmissionpage/create',[App\Http\Controllers\Admin\SlideController::class, 'createPost']) -> name('slidespost.create');
    Route::get('slidessubmissionpage/edit/{id}',[App\Http\Controllers\Admin\SlideController::class, 'editPost']) -> name('slidespost.edit');
    Route::get('slidessubmissionpage/viewAll/{submissionPostId}', [App\Http\Controllers\Admin\SlideController::class, 'getSlidesSubmissionForLecturer'])->name('slidespost.showAll');
    Route::get('slidessubmissionpage/viewOne/{slideSubmissionId}', [App\Http\Controllers\Admin\SlideController::class, 'showSlidesSubmissions'])->name('slidespost.show');

    // assign student advisor
    Route::get('advisor-assignment/create', [App\Http\Controllers\Admin\SupervisorStudentAssignmentController::class, 'create'])->name('advisor-assignment.create');
    Route::post('advisor-assignment/store', [App\Http\Controllers\Admin\SupervisorStudentAssignmentController::class, 'store'])->name('advisor-assignment.store');


    // for calendar - admin view
    Route::get('/calendar', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'getEvents'])->name('calendar.index');;
    Route::get('/calendar/events/{date}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'getDateEvents']);
    Route::get('/calendar/{eventId}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'getEventDetails']);
    Route::post('/calendar/update/{eventId}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'updateEvent']);
    Route::post('/calendar/store', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'store'])->name('calendar.store');
    Route::post('/calendar/updateEvent/{id}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'changeEvent']);
    // Route::post('/calendar/updatedrag-event', [App\Http\Controllers\Admin\PresentationScheduleController::class,  'updateDragEvent']);

    Route::post('calendar/uploadSche', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'uploadPresentationSche'])->name('calendarsche.upload');
    // Route::get('calendar/showAllFile',[App\Http\Controllers\Admin\FormController::class, 'showAllFile']) -> name('calendarsche.showfile');
    Route::get('calendar/ii',[App\Http\Controllers\Admin\PresentationScheduleController::class, 'showAllFile']) -> name('calendarsche.showfile');
    Route::get('/download/template/{id}', [App\Http\Controllers\Admin\PresentationScheduleController::class,'downloadTemplate'])->name('download.template');
    // web.php or routes file
    Route::get('/delete/template/{id}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'deleteTemplate'])->name('delete.template');

    Route::delete('/calendar/delete-event/{eventId}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'deleteEvent']);

    // DRAGGABLE EVENTS
    Route::post('/calendar/dragevents', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'storeDrag'])->name('calendarsche.storedrag');

    // THESIS REPO
    Route::get('/thesis', [App\Http\Controllers\Admin\FormController::class, 'indexVue'])->middleware('cors');

});

// Route::delete('/admin/formsubmissionpage/{id}/deletefile/{file}', 'FormController@deleteFile')->name('formpost.deletefile');
Route::group(['middleware' => ['auth', 'isLecturer'], 'prefix' => 'lecturer'], function () {
    // Route::middleware(['middleware' => 'auth','isLecturer', 'prefix' => 'lecturer'],function () {
    Route::get('dashboard',[App\Http\Controllers\Admin\DashboardController::class, 'indexLect']) -> name('lect_dashboard');

    // FORM
    // templates
    Route::get('formpage/admintemplateupload',[App\Http\Controllers\Admin\FormController::class, 'index']) -> name('formtemplate.index');
    Route::get('formpage/admintemplateupload/create',[App\Http\Controllers\Admin\FormController::class, 'create']) -> name('template.create');
    // Unified for all type templates
    Route::post('formpage/admintemplateupload/create',[App\Http\Controllers\Admin\FormController::class, 'store']) -> name('template.store');
    // Route::get('formpage/admintemplateupload/{id}',[App\Http\Controllers\Admin\FormController::class, 'show']) -> name('template.show');
    Route::get('formpage/admintemplateupload/edit/{id}',[App\Http\Controllers\Admin\FormController::class, 'edit']) -> name('template.edit');
    Route::put('formpage/admintemplateupload/edit/{id}',[App\Http\Controllers\Admin\FormController::class, 'update']) -> name('template.updatew');
    Route::delete('formpage/admintemplateupload/delete/{id}',[App\Http\Controllers\Admin\FormController::class, 'destroy']) -> name('template.destroy');
    Route::delete('formpage/remove-file/{id}',  [App\Http\Controllers\Admin\FormController::class, 'removeTemplateFile'])->name('template.remove-file');
    //submissionpost

    // calendar
    Route::get('/calendar', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'getEvents'])->name('calendar.index');;
    Route::get('/calendar/events/{date}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'getDateEvents']);
    Route::get('/calendar/{eventId}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'getEventDetails']);
    Route::post('/calendar/update/{eventId}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'updateEvent']);
    Route::post('/calendar/store', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'store'])->name('calendar.store');
    Route::post('/calendar/updateEvent/{id}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'changeEvent']);

    Route::post('calendar/uploadSche', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'uploadPresentationSche'])->name('calendarsche.upload');
    Route::get('calendar/ii',[App\Http\Controllers\Admin\PresentationScheduleController::class, 'showAllFile']) -> name('calendarsche.showfile');
    Route::get('/download/template/{id}', [App\Http\Controllers\Admin\PresentationScheduleController::class,'downloadTemplate'])->name('download.template');
    Route::get('/delete/template/{id}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'deleteTemplate'])->name('delete.template');

    Route::delete('/calendar/delete-event/{eventId}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'deleteEvent']);
    Route::post('/calendar/dragevents', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'storeDrag'])->name('calendarsche.storedrag');

    // SUPERVISED STUDENT
    Route::get('advisor-assignment', [App\Http\Controllers\Admin\SupervisorStudentAssignmentController::class, 'index'])->name('advisor-assignment.index');

});

Route::group(['middleware' => 'auth','isStudent', 'prefix' => 'student'], function(){

    Route::get('/notifications', [App\Http\Controllers\Admin\NotiController::class, 'indexStu']);
    Route::post('/mark-notification-as-read/{notificationId}', [App\Http\Controllers\Admin\NotiController::class,'markNotificationAsRead']);

    Route::get('dashboard',[App\Http\Controllers\Admin\DashboardController::class, 'index']) -> name('student_dashboard');
    Route::get('student/studentlist',[App\Http\Controllers\Admin\DashboardController::class, 'test']);

    Route::get('calendar', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'getStuEvents'])->name('stucalendar.index');;
    Route::get('/calendar/events/{date}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'getDateEvents']);
    Route::get('/calendar/{eventId}', [App\Http\Controllers\Admin\PresentationScheduleController::class, 'getEventDetails']);

    // FORMS SECTION
    Route::get('form/submission',[App\Http\Controllers\Student\FormController::class, 'index']) -> name('stutemplate.index');
    Route::get('form/submission/create/{submission_post_id}',[App\Http\Controllers\Student\FormController::class, 'createFormSubmission']) -> name('stuFormSubmission.create');
    Route::post('form/submission/create',[App\Http\Controllers\Student\FormController::class, 'storeFormSubmission']) -> name('stuFormSubmission.store');
    Route::delete('form/submission/{formSubmissionId}', [App\Http\Controllers\Student\FormController::class, 'deleteFormSubmission'])->name('formSubmission.delete');
    Route::get('/form/submission/edit/{formSubmissionId}/{submissionPostId}', [App\Http\Controllers\Student\FormController::class, 'editFormSubmission'])->name('formSubmission.edit');
    Route::post('/form/submission/{formSubmissionId}', [App\Http\Controllers\Student\FormController::class, 'updateFormSubmission'])->name('formSubmission.update');
    Route::delete('/form/submission/remove-file/{formSubmissionId}',  [App\Http\Controllers\Student\FormController::class, 'removeFile'])->name('formSubmission.remove-file');

    // for commment part
    Route::post('form/submission/{formSubmissionId}/addComment', [App\Http\Controllers\CommentController::class, 'addStuCommentToFormSubmission'])->name('stuFormSubmission.addComment');
    Route::get('form/submission/{submissionPostId}', [App\Http\Controllers\Student\FormController::class, 'showStuFormSubmissionDetails'])->name('stuFormSubmission.details');
    Route::get('comment/delete/{commentId}', [App\Http\Controllers\CommentController::class, 'deleteStuComment'])->name('stuFormSubmission.deletecomment');


    // FOR THESIS section
    Route::get('thesis/submission',[App\Http\Controllers\Student\FormController::class, 'indexThesis']) -> name('stuThesistemplate.index');
    Route::get('thesis/submission/{submissionPostId}', [App\Http\Controllers\Student\FormController::class, 'showStuThesisSubmissionDetails'])->name('stuThesisSubmission.details');
    Route::get('thesis/submission/create/{submission_post_id}',[App\Http\Controllers\Student\FormController::class, 'createThesisSubmission']) -> name('stuThesisSubmission.create');
    Route::post('thesis/submission/create',[App\Http\Controllers\Student\FormController::class, 'storeThesisSubmission']) -> name('stuThesisSubmission.store');
    Route::delete('thesis/submission/delete/{thesisSubmissionId}', [App\Http\Controllers\Student\FormController::class, 'deleteThesisSubmission'])->name('thesisSubmission.delete');
    Route::get('thesis/submission/edit/{thesisSubmissionId}/{submissionPostId}', [App\Http\Controllers\Student\FormController::class, 'editThesisSubmission'])->name('thesisSubmission.edit');
    Route::post('thesis/submission/{thesisSubmissionId}', [App\Http\Controllers\Student\FormController::class, 'updateThesisSubmission'])->name('thesisSubmission.update');
    Route::delete('thesis/submission/remove-file/{thesisSubmissionId}',  [App\Http\Controllers\Student\FormController::class, 'removeThesisFile'])->name('thesisSubmission.remove-file');

    Route::post('thesis/submission/addcomment/{thesisSubmissionId}', [App\Http\Controllers\CommentController::class, 'addCommentToThesisSubmission'])->name('stuThesisSubmission.addComment');
    Route::get('thesiscomment/delete/{commentId}', [App\Http\Controllers\CommentController::class, 'deleteStuThesisComment'])->name('stuThesisSubmission.deletecomment');

    // REPORT SECTION, PROPOSAL
    Route::get('proposal/submission',[App\Http\Controllers\Student\ReportController::class, 'index']) -> name('stuProposaltemplate.index');
    Route::get('proposal/submission/{submissionPostId}', [App\Http\Controllers\Student\ReportController::class, 'showStuProposalSubmissionDetails'])->name('stuProposalSubmission.details');
    Route::get('proposal/submission/create/{submission_post_id}',[App\Http\Controllers\Student\ReportController::class, 'createProposalSubmission']) -> name('stuProposalSubmission.create');
    Route::post('proposal/submission/create',[App\Http\Controllers\Student\ReportController::class, 'storeProposalSubmission']) -> name('stuProposalSubmission.store');
    Route::get('proposal/submission/edit/{proposalSubmissionId}/{submissionPostId}', [App\Http\Controllers\Student\ReportController::class, 'editProposalSubmission'])->name('proposalSubmission.edit');
    Route::post('proposal/submission/{proposalSubmissionId}', [App\Http\Controllers\Student\ReportController::class, 'updateProposalSubmission'])->name('proposalSubmission.update');
    Route::delete('proposal/submission/delete/{proposalSubmissionId}', [App\Http\Controllers\Student\ReportController::class, 'deleteProposalSubmission'])->name('proposalSubmission.delete');
    Route::delete('proposal/submission/remove-file/{submissionPostId}',  [App\Http\Controllers\Student\ReportController::class, 'removeFile'])->name('proposalSubmission.remove-file');

    Route::post('proposal/submission/addcomment/{proposalSubmissionId}', [App\Http\Controllers\CommentController::class, 'addCommentToProposalSubmission'])->name('stuProposalSubmission.addComment');

    // SLDIE SECTION
    Route::get('slide/submission',[App\Http\Controllers\Student\SlideController::class, 'index']) -> name('stuSlidetemplate.index');
    Route::get('slide/submission/{submissionPostId}', [App\Http\Controllers\Student\SlideController::class, 'showStuSlideSubmissionDetails'])->name('stuSlideSubmission.details');
    Route::get('slide/submission/create/{submission_post_id}',[App\Http\Controllers\Student\SlideController::class, 'createSlideSubmission']) -> name('stuSlideSubmission.create');
    Route::post('slide/submission/create',[App\Http\Controllers\Student\SlideController::class, 'storeSlideSubmission']) -> name('stuSlideSubmission.store');
    Route::get('slide/submission/edit/{slideSubmissionId}/{submissionPostId}', [App\Http\Controllers\Student\SlideController::class, 'editSlideSubmission'])->name('slideSubmission.edit');
    Route::post('slide/submission/{slideSubmissionId}', [App\Http\Controllers\Student\SlideController::class, 'updateSlideSubmission'])->name('slideSubmission.update');
    Route::delete('slide/submission/delete/{slideSubmissionId}', [App\Http\Controllers\Student\SlideController::class, 'deleteSlideSubmission'])->name('slideSubmission.delete');
    Route::delete('slide/submission/remove-file/{submissionPostId}',  [App\Http\Controllers\Student\SlideController::class, 'removeFile'])->name('slideSubmission.remove-file');

    Route::post('slide/submission/addcomment/{slideSubmissionId}', [App\Http\Controllers\CommentController::class, 'addCommentToSlideSubmission'])->name('stuSlideSubmission.addComment');
    Route::get('/thesis', [App\Http\Controllers\Student\FormController::class, 'indexVue'])->middleware('cors')->name('thesis.index');

});

// Route::get('/admin/dashboard');

// Route::get('/admin/dashboard',function(){
//     return view('welcome');
// })->where('pathMatch',".*");


// Route::get('{view}', ApplicationController::class)-> where('view','(*)');