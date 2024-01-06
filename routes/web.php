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

// must add a auth there!!
Route::group(['middleware' => ['auth', 'isAdmin'], 'prefix' => 'admin'], function () {

    Route::get('/notifications', [App\Http\Controllers\Admin\NotiController::class, 'index']);

    // to get the admin dashboard
    Route::get('dashboard',[App\Http\Controllers\Admin\DashboardController::class, 'index']) -> name('admin_dashboard');


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
    Route::delete('formsubmissionpage/remove-file/{submissionPostId}',  [App\Http\Controllers\Admin\FormController::class, 'removeFile'])->name('formpost.remove-file');

    //view form submission post for each student
    Route::get('formsubmissionpage/viewAll/{submissionPostId}', [App\Http\Controllers\Admin\FormController::class, 'getFormSubmissionForLecturer'])->name('formpost.showAllAdmin');
    Route::get('formsubmissionpage/viewOne/{formSubmissionId}', [App\Http\Controllers\Admin\FormController::class, 'showFormSubmissions'])->name('formpost.show');
    Route::post('formsubmissionpage/viewAll/download-all-files', [App\Http\Controllers\Admin\FormController::class, 'downloadAllFiles']);

    // for comments
    Route::post('formsubmissionpage/viewOne/{formSubmissionId}/addComment', [App\Http\Controllers\CommentController::class, 'addCommentToFormSubmission'])->name('formpostShow.addComment');
    Route::get('formsubmissionpage/viewOne/comment/delete/{commentId}', [App\Http\Controllers\CommentController::class, 'deleteLectComment'])->name('adminFormSubmission.deletecomment');

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
    Route::delete('thesissubmissionpage/remove-file/{submissionPostId}',  [App\Http\Controllers\Admin\ThesisController::class, 'removeFile'])->name('thesispost.remove-file');

    Route::get('thesissubmissionpage/viewAll/{submissionPostId}', [App\Http\Controllers\Admin\ThesisController::class, 'getThesisSubmissionForLecturer'])->name('thesispost.showAll');
    Route::get('thesissubmissionpage/viewOne/{thesisSubmissionId}', [App\Http\Controllers\Admin\ThesisController::class, 'showThesisSubmissions'])->name('thesispost.show');
    // Add a custom route for updating thesis status
    // Route::patch('/thesissubmissionpage/{thesisSubmission}/update-status', [App\Http\Controllers\Admin\ThesisController::class, 'updateStatus'])
    //     ->name('updateThesisStatus');
    Route::put('/update-thesis-status/{studentId}', [App\Http\Controllers\Admin\ThesisController::class, 'updateStatus'])->name('updateThesisStatus');

    // for comments
    Route::post('thesissubmissionpage/viewOne/{thesisSubmissionId}/addComment', [App\Http\Controllers\CommentController::class, 'addCommentToThesisSubmission'])->name('thesispostShow.addComment');
    Route::get('thesissubmissionpage/viewOne/comment/delete/{commentId}', [App\Http\Controllers\CommentController::class, 'deleteLectComment'])->name('adminFormSubmission.deletecomment');

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

    // for comments
    Route::post('proposalsubmissionpage/viewOne/{proposalSubmissionId}/addComment', [App\Http\Controllers\CommentController::class, 'addCommentToProposalSubmission'])->name('proposalpostShow.addComment');
    Route::get('proposalsubmissionpage/viewOne/comment/delete/{commentId}', [App\Http\Controllers\CommentController::class, 'deleteLectComment'])->name('adminFormSubmission.deletecomment');

    // SLIDES SECTION
    Route::get('slidessubmissionpage/admintemplatelist',[App\Http\Controllers\Admin\SlideController::class, 'index']) -> name('slidestemplate.index');
    Route::get('slidessubmissionpage/admintemplatelist/create',[App\Http\Controllers\Admin\SlideController::class, 'create']) -> name('slidestemplate.create');
    Route::get('slidessubmissionpage/admintemplatelist/edit/{id}',[App\Http\Controllers\Admin\SlideController::class, 'edit']) -> name('slidestemplate.edit');

    Route::get('slidessubmissionpage/allpost',[App\Http\Controllers\Admin\SlideController::class, 'indexPost']) -> name('slidespost.index');
    Route::get('slidessubmissionpage/create',[App\Http\Controllers\Admin\SlideController::class, 'createPost']) -> name('slidespost.create');
    Route::get('slidessubmissionpage/edit/{id}',[App\Http\Controllers\Admin\SlideController::class, 'editPost']) -> name('slidespost.edit');
    Route::get('slidessubmissionpage/viewAll/{submissionPostId}', [App\Http\Controllers\Admin\SlideController::class, 'getSlidesSubmissionForLecturer'])->name('slidespost.showAll');
    Route::get('slidessubmissionpage/viewOne/{slideSubmissionId}', [App\Http\Controllers\Admin\SlideController::class, 'showSlidesSubmissions'])->name('slidespost.show');
   // for comments
   Route::post('slidessubmissionpage/viewOne/{slideSubmissionId}/addComment', [App\Http\Controllers\CommentController::class, 'addCommentToSlideSubmission'])->name('slidespostShow.addComment');
   Route::get('slidessubmissionpage/viewOne/comment/delete/{commentId}', [App\Http\Controllers\CommentController::class, 'deleteLectComment'])->name('adminFormSubmission.deletecomment');

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



Route::group(['middleware' => ['auth', 'isLecturer'], 'prefix' => 'lecturer'], function () {
    // Route::middleware(['middleware' => 'auth','isLecturer', 'prefix' => 'lecturer'],function () {
    Route::get('dashboard',[App\Http\Controllers\Admin\DashboardController::class, 'indexLect']) -> name('lect_dashboard');

    // FORM
    // templates
    Route::get('formpage/admintemplateupload', [App\Http\Controllers\Lecturer\FormController::class, 'index'])->name('lecttemplate.index');
    Route::get('formpage/admintemplateupload/create', [App\Http\Controllers\Lecturer\FormController::class, 'create'])->name('lecttemplate.create');
    // Unified for all type templates
    Route::post('formpage/admintemplateupload/create', [App\Http\Controllers\Lecturer\FormController::class, 'store'])->name('lecttemplate.store');
    Route::get('formpage/admintemplateupload/{id}', [App\Http\Controllers\Lecturer\FormController::class, 'show'])->name('lecttemplate.show');
    Route::get('formpage/admintemplateupload/edit/{id}', [App\Http\Controllers\Lecturer\FormController::class, 'edit'])->name('lecttemplate.edit');
    Route::put('formpage/admintemplateupload/edit/{id}', [App\Http\Controllers\Lecturer\FormController::class, 'update'])->name('lecttemplate.updatew');
    Route::delete('formpage/admintemplateupload/delete/{id}', [App\Http\Controllers\Lecturer\FormController::class, 'destroy'])->name('lecttemplate.destroy');
    Route::delete('formpage/remove-file/{id}', [App\Http\Controllers\Lecturer\FormController::class, 'removeTemplateFile'])->name('lecttemplate.remove-file');

    // form submission post
    Route::get('formsubmissionpage/allpost', [App\Http\Controllers\Lecturer\FormController::class, 'indexPost'])->name('lectformpost.index');
    Route::get('formsubmissionpage/create', [App\Http\Controllers\Lecturer\FormController::class, 'createPost'])->name('lectformpost.create');
    Route::get('formsubmissionpage/edit/{id}', [App\Http\Controllers\Lecturer\FormController::class, 'editPost'])->name('lectformpost.edit');

    // unify for all templates
    Route::post('formsubmissionpage/create', [App\Http\Controllers\Lecturer\FormController::class, 'storePost'])->name('lectformpost.store');
    Route::delete('formsubmissionpage/delete/{id}', [App\Http\Controllers\Lecturer\FormController::class, 'destroyPost'])->name('lectformpost.destroy');
    Route::put('formsubmissionpage/update/{id}', [App\Http\Controllers\Lecturer\FormController::class, 'updatePost'])->name('lectformpost.update');
    Route::delete('formsubmissionpage/remove-file/{submissionPostId}', [App\Http\Controllers\Lecturer\FormController::class, 'removeFile'])->name('lectformpost.remove-file');

    //view form submission post for each student
    Route::get('formsubmissionpage/viewAll/{submissionPostId}', [App\Http\Controllers\Lecturer\FormController::class, 'getFormSubmissionForLecturer'])->name('lectformpost.showAllLecturer');
    Route::get('formsubmissionpage/viewOne/{formSubmissionId}', [App\Http\Controllers\Lecturer\FormController::class, 'showFormSubmissions'])->name('lectformpost.show');
    Route::post('formsubmissionpage/viewAll/download-all-files', [App\Http\Controllers\Lecturer\FormController::class, 'downloadAllFiles']);

    // for comments
    Route::post('formsubmissionpage/viewOne/{formSubmissionId}/addComment', [App\Http\Controllers\CommentController::class, 'addCommentToFormSubmission'])->name('formpostShow.addComment');
    Route::get('formsubmissionpage/viewOne/comment/delete/{commentId}', [App\Http\Controllers\CommentController::class, 'deleteLect2Comment'])->name('lectFormSubmission.deletecomment');

    // PROPOSAL SECTION
    Route::get('proposalsubmissionpage/admintemplatelist', [App\Http\Controllers\Lecturer\ProposalController::class, 'index'])->name('lectproptemplate.index');
    Route::get('proposalsubmissionpage/admintemplatelist/create', [App\Http\Controllers\Lecturer\ProposalController::class, 'create'])->name('lectproptemplate.create');
    Route::get('proposalsubmissionpage/admintemplatelist/edit/{id}', [App\Http\Controllers\Lecturer\ProposalController::class, 'edit'])->name('lectproptemplate.edit');

    Route::get('proposalsubmissionpage/allpost', [App\Http\Controllers\Lecturer\ProposalController::class, 'indexPost'])->name('lectproposalpost.index');
    Route::get('proposalsubmissionpage/create', [App\Http\Controllers\Lecturer\ProposalController::class, 'createPost'])->name('lectproposalpost.create');
    Route::post('proposalsubmissionpage/create', [App\Http\Controllers\Lecturer\ProposalController::class, 'storePost'])->name('lectproposalpost.store');
    Route::get('proposalsubmissionpage/edit/{id}', [App\Http\Controllers\Lecturer\ProposalController::class, 'editPost'])->name('lectproposalpost.edit');

    Route::get('proposalsubmissionpage/viewAll/{submissionPostId}', [App\Http\Controllers\Lecturer\ProposalController::class, 'getProposalSubmissionForLecturer'])->name('lectproposalpost.showAll');
    Route::get('proposalsubmissionpage/viewOne/{proposalSubmissionId}', [App\Http\Controllers\Lecturer\ProposalController::class, 'showProposalSubmissions'])->name('lectproposalpost.show');

    // for comments
    Route::post('proposalsubmissionpage/viewOne/{proposalSubmissionId}/addComment', [App\Http\Controllers\CommentController::class, 'addCommentToProposalSubmission'])->name('lectproposalpostShow.addComment');
    Route::get('proposalsubmissionpage/viewOne/comment/delete/{commentId}', [App\Http\Controllers\CommentController::class, 'deleteLect2Comment'])->name('lectFormSubmission.deletecomment');

    // THESIS
    Route::get('thesissubmissionpage/admintemplatelist', [App\Http\Controllers\Lecturer\ThesisController::class, 'index'])->name('lectthesistemplate.index');
    Route::get('thesissubmissionpage/admintemplatelist/create', [App\Http\Controllers\Lecturer\ThesisController::class, 'create'])->name('lectthesistemplate.create');
    Route::get('thesissubmissionpage/admintemplatelist/edit/{id}', [App\Http\Controllers\Lecturer\ThesisController::class, 'edit'])->name('lectthesistemplate.edit');

    // thesis submission post
    Route::get('thesissubmissionpage/allpost', [App\Http\Controllers\Lecturer\ThesisController::class, 'indexPost'])->name('lectthesispost.index');
    Route::get('thesissubmissionpage/create', [App\Http\Controllers\Lecturer\ThesisController::class, 'createPost'])->name('lectthesispost.create');
    Route::post('thesissubmissionpage/create', [App\Http\Controllers\Lecturer\ThesisController::class, 'storePost'])->name('lectthesispost.store');
    Route::delete('thesissubmissionpage/delete/{id}', [App\Http\Controllers\Lecturer\ThesisController::class, 'destroyPost'])->name('lectthesispost.destroy');
    Route::get('thesissubmissionpage/edit/{id}', [App\Http\Controllers\Lecturer\ThesisController::class, 'editPost'])->name('lectthesispost.edit');
    Route::put('thesissubmissionpage/update/{id}', [App\Http\Controllers\Lecturer\ThesisController::class, 'updatePost'])->name('lectthesispost.update');
    Route::delete('thesissubmissionpage/remove-file/{submissionPostId}',  [App\Http\Controllers\Lecturer\ThesisController::class, 'removeFile'])->name('lectthesispost.remove-file');

    Route::get('thesissubmissionpage/viewAll/{submissionPostId}', [App\Http\Controllers\Lecturer\ThesisController::class, 'getThesisSubmissionForLecturer'])->name('lectthesispost.showAll');
    Route::get('thesissubmissionpage/viewOne/{thesisSubmissionId}', [App\Http\Controllers\Lecturer\ThesisController::class, 'showThesisSubmissions'])->name('lectthesispost.show');
    // Add a custom route for updating thesis status
    // Route::patch('/thesissubmissionpage/{submission}/update-status', [App\Http\Controllers\Lecturer\ThesisController::class, 'updateStatus'])
    //     ->name('updateThesisStatus');

    // for comments
    Route::post('thesissubmissionpage/viewOne/{thesisSubmissionId}/addComment', [App\Http\Controllers\CommentController::class, 'addCommentToThesisSubmission'])->name('lectthesispostShow.addComment');
    Route::get('thesissubmissionpage/viewOne/comment/delete/{commentId}', [App\Http\Controllers\CommentController::class, 'deleteLect2Comment'])->name('lectFormSubmission.deletecomment');

    // SLIDES SECTION
    // templates
    Route::get('slidessubmissionpage/admintemplatelist', [App\Http\Controllers\Lecturer\SlideController::class, 'index'])->name('lectslidestemplate.index');
    Route::get('slidessubmissionpage/admintemplatelist/create', [App\Http\Controllers\Lecturer\SlideController::class, 'create'])->name('lectslidestemplate.create');
    Route::get('slidessubmissionpage/admintemplatelist/edit/{id}', [App\Http\Controllers\Lecturer\SlideController::class, 'edit'])->name('lectslidestemplate.edit');

    Route::get('slidessubmissionpage/allpost', [App\Http\Controllers\Lecturer\SlideController::class, 'indexPost'])->name('lectslidespost.index');
    Route::get('slidessubmissionpage/create', [App\Http\Controllers\Lecturer\SlideController::class, 'createPost'])->name('lectslidespost.create');
    Route::get('slidessubmissionpage/edit/{id}', [App\Http\Controllers\Lecturer\SlideController::class, 'editPost'])->name('lectslidespost.edit');
    Route::get('slidessubmissionpage/viewAll/{submissionPostId}', [App\Http\Controllers\Lecturer\SlideController::class, 'getSlidesSubmissionForLecturer'])->name('lectslidespost.showAll');
    Route::get('slidessubmissionpage/viewOne/{slideSubmissionId}', [App\Http\Controllers\Lecturer\SlideController::class, 'showSlidesSubmissions'])->name('lectslidespost.show');
    Route::delete('slidessubmissionpage/remove-file/{submissionPostId}',  [App\Http\Controllers\Lecturer\SlideController::class, 'removeFile'])->name('lectslidespost.remove-file');

    // for comments
    Route::post('proposalsubmissionpage/viewOne/{slideSubmissionId}/addComment', [App\Http\Controllers\CommentController::class, 'addCommentToSlideSubmission'])->name('lectslidespostShow.addComment');
    Route::get('proposalsubmissionpage/viewOne/comment/delete/{commentId}', [App\Http\Controllers\CommentController::class, 'deleteLectComment'])->name('lectFormSubmission.deletecomment');

    // for calendar - lecturer view
    Route::get('/calendar', [App\Http\Controllers\Lecturer\PresentationScheduleController::class, 'getEvents'])->name('calendar.index');
    Route::get('/calendar/events/{date}', [App\Http\Controllers\Lecturer\PresentationScheduleController::class, 'getDateEvents']);
    Route::get('/calendar/{eventId}', [App\Http\Controllers\Lecturer\PresentationScheduleController::class, 'getEventDetails']);
    Route::post('/calendar/update/{eventId}', [App\Http\Controllers\Lecturer\PresentationScheduleController::class, 'updateEvent']);
    Route::post('/calendar/store', [App\Http\Controllers\Lecturer\PresentationScheduleController::class, 'store'])->name('calendar.store');
    Route::post('/calendar/updateEvent/{id}', [App\Http\Controllers\Lecturer\PresentationScheduleController::class, 'changeEvent']);
    // Route::post('/calendar/updatedrag-event', [App\Http\Controllers\Lecturer\PresentationScheduleController::class,  'updateDragEvent']);

    Route::post('/calendar/uploadSche', [App\Http\Controllers\Lecturer\PresentationScheduleController::class, 'uploadPresentationSche'])->name('lectcalendarsche.upload');
    Route::get('calendar/ii', [App\Http\Controllers\Lecturer\PresentationScheduleController::class, 'showAllFile'])->name('calendarsche.showfile');
    Route::get('/download/template/{id}', [App\Http\Controllers\Lecturer\PresentationScheduleController::class, 'downloadTemplate'])->name('download.template');
    Route::get('/delete/template/{id}', [App\Http\Controllers\Lecturer\PresentationScheduleController::class, 'deleteTemplate'])->name('delete.template');
    Route::delete('/calendar/delete-event/{eventId}', [App\Http\Controllers\Lecturer\PresentationScheduleController::class, 'deleteEvent']);

    // DRAGGABLE EVENTS
    Route::post('/calendar/dragevents', [App\Http\Controllers\Lecturer\PresentationScheduleController::class, 'storeDrag'])->name('lectcalendarsche.storedrag');

    // SUPERVISED STUDENT
    Route::get('advisor-assignment', [App\Http\Controllers\Admin\SupervisorStudentAssignmentController::class, 'index'])->name('advisor-assignment.index');

    // THESIS REPO
    Route::get('/thesis', [App\Http\Controllers\Admin\FormController::class, 'indexVueLect'])->middleware('cors');

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