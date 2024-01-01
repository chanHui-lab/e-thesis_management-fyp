<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Template;
use App\Models\SubmissionPost;
use App\Models\Student;
use App\Models\Comment;
use App\Models\Slide_submission;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// reminder
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

//filevalidation
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class SlideController extends Controller
{

    public function index()
    {
        $template = Template::getSlideTemplate();

        return view('admin.slide_page.allSlidesTemplate',compact('template'));
    }

    public function create()
    {
        return view('admin.slide_page.createSlidesTemplate');
    }

    public function edit( $id)
    {
        $data['getRecord'] = Template::getSingle($id);
        if(!empty($data['getRecord'])){
            return view('admin.slide_page.editSlidesTemplate',$data);
        }
        else{
            abort(404);
        }
    }

    // ---------------------------------------------------------

    public function indexPost()
    {

        $post = SubmissionPost::getAdminSlidesSP();
        // dd( $post);
        // Calculate remaining time for each submission
        foreach ($post as $postform) {
            $postform->remainingTime = $this->calculateRemainingTime($postform->submission_deadline);
        }

        return view('admin.slide_page.allslidespost',compact('post'));

    }

    public function calculateRemainingTime($deadline)
    {
        $deadlineTime = Carbon::parse($deadline);
        $currentTime = Carbon::now();

        $remainingTime = $currentTime->diff($deadlineTime);

        return $remainingTime;
    }

    public function createPost()
    {
        return view('admin.slide_page.create_slideS');
    }

    public function editPost($id)
    {
        $data['getRecord'] = SubmissionPost::getSingle($id);

        if(!empty($data['getRecord'])){
            // dd($data);
            return view('admin.slide_page.editSlidesF', $data);
        }
        else{
            return redirect()->route('slidespost.index')->with('error', 'Submission post not found');
        }
    }

    public function getSlidesSubmissionForLecturer($submissionPostId){
        $lecturer = auth()->user(); // Get the currently logged-in lecturer
        // $submissionPost = SubmissionPost::find($submissionPostId);
        $submissionPost = SubmissionPost::with('lecturer')->find($submissionPostId);

        // added accesible for admin role
        if (auth()->user()->role_as == 0) {

            // dd($submissionPost);

            $slideSubmissions = Slide_submission::where('submission_post_id', $submissionPost->id)
            ->get();
            // dd( $slideSubmissions);

            $students = DB::table('students')
            ->select('students.stu_id', 'users.name AS student_name', 'students.matric_number', 'slide_submissions.*')
            ->leftJoin('users', 'students.stu_id', '=', 'users.id')
            ->leftJoin('slide_submissions', 'students.stu_id', '=', 'slide_submissions.student_id')
            ->get();
            // dd( $students);
            return view('admin.slide_page.viewAllSlides', compact('slideSubmissions','students','submissionPost'));

        }
        elseif(auth()->user()->role_as == 1){
            if (($submissionPost && $submissionPost->lecturer && $submissionPost->lecturer->id === $lecturer->id)) {

            // NEED TO ADD ONE PART, if lecture role is admin, access all student instead of joinging.
            // if its lecturer, asses the one she created and access all the admin.
            $slideSubmissions = $lecturer->formSubmissions()
            ->where('submission_post_id', $submissionPost->id)
            ->get();

            $slideSubmissions = Slide_submission::where('submission_post_id', $submissionPost->id)
            ->get();

            // THIS IS FOR LECTURER INTERFACE!! ONLY SUPERVISED STUDENTS
            $students = DB::table('students')
            ->select('students.stu_id', 'users.name AS student_name', 'students.matric_number')
            ->leftJoin('users', 'students.stu_id', '=', 'users.id')
            ->leftJoin('slide_submissions', function ($join) use ($lecturer, $submissionPost) {
                $join->on('students.stu_id', '=', 'slide_submissions.student_id')
                    ->where('slide_submissions.submission_post_id', $submissionPost->id);
            })
            ->where(function ($query) use ($lecturer) {
                $query->where('students.supervisor_id', $lecturer->id)
                    ->orWhereNull('slide_submissions.student_id');
            })
            ->get();

            return view('admin.report_page.proposal.viewAllProposal', compact('slideSubmissions','students','submissionPost'));
        }

        }
    }

    public function showSlidesSubmissions($slideSubmissionId)

    {
        // $submissionPost = SubmissionPost::findOrFail($submissionPostId);
        $slideSubmission = Slide_submission::findOrFail($slideSubmissionId);
        // $student = Student::findOrFail($studentId);
        $submissionPostId = $slideSubmission->submissionPost->id;
        $comments = Comment::where('commentable_id', $slideSubmissionId)
        ->where(function ($query) use ($slideSubmission) {
            // Include comments made by the student
            $query->where('lecturer_id', Auth::id())
                // Include comments made by the lecturer who supervises the student
                ->orWhere('student_id', $slideSubmission->student_id);
        })
        ->get();
        $allcomments = $slideSubmission->comments;

        return view('admin.slide_page.viewOneSlide', compact('slideSubmission','submissionPostId', 'comments'));
    }

}
