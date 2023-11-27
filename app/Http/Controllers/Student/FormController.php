<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use App\Models\Template;
use App\Models\SubmissionPost;
use App\Models\Student;
use App\Models\Form_submission;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    public function index()
    {

        $template = Template::getStuFormTemplate();
        // dd($template);
        // $template = Template::getAdminFormTemplate();

        // $post = SubmissionPost::getStuFormSP();
        // dd($post );
        $user = Auth::user();

        $post = SubmissionPost::with('lecturer')
            ->where(function ($query) use ($user) {
                // Posts created by the student's supervisor
                $query->where('lecturer_id', $user->supervisor_id)
                      ->orWhere(function ($subquery) {
                          // Posts created by admins (role_as = 0)
                          $subquery->whereHas('lecturer', function ($lecturerQuery) {
                              $lecturerQuery->where('role_as', 0);
                          });
                      });
            })
            ->where('section', 'form')
            ->get();

        // I want to use the submission status, soo.....
        $submissionDetails = [];

        foreach ($post as $submissionPost) {
            $formSubmission = $submissionPost->formSubmissions()
                ->where('student_id', $user->id)
                ->first();

        if ($formSubmission) {
            // Form has been submitted
            $status = 'Submitted';
            $chipClass = 'bg-light-blue text-white'; // Adjust the class based on your styling
        } else {
            // Form has not been submitted
            $submissionDeadline = \Carbon\Carbon::parse($submissionPost->submission_deadline);
            $status = $submissionDeadline->isFuture() ? 'Pending' : 'Overdue';
            $chipClass = $submissionDeadline->isFuture() ? 'bg-light-green text-black' : 'bg-light-red text-white';
        }

        $submissionDetails[] = [
            'submissionPost' => $submissionPost,
            'formSubmission' => $formSubmission,
            'status' => $status,
            'chipClass' => $chipClass,
        ];
    }
        return view('student.stuform.formTemplate&Submission',compact('template','submissionDetails'));
    }

    // public function indexPost()
    // {

    //     // define nem of varibale then use back
    //     // $template = Template::latest()->paginate(3);
    //     $post = SubmissionPost::getAdminFormSP();

    //     // Calculate remaining time for each submission
    //     // foreach ($post as $postform) {
    //     //     $postform->remainingTime = $this->calculateRemainingTime($postform->submission_deadline);
    //     // }

    //     return view('student.stuform.formTemplate&Submission',compact('post'));

    // }
    public function showStuFormSubmissionDetails($id)
    {
        // Fetch the submission post details
        // $submissionPost = SubmissionPost::getSingle($id);
        $submissionPost = SubmissionPost::where('id', $id)->first();

        // $submissionPost = DB::table('submission_posts')->find(40);

        // dd($submissionPost);

        if (!$submissionPost) {
            abort(404); // or handle the error in another way, e.g., redirect
        }

        // Check if the current user has permission to view this submission
        // You may need to implement your own logic for permission checking

        // Fetch form submissions related to the submission post for the current student
        $formSubmissions = $submissionPost->formSubmissions()
            ->where('student_id', Auth::id())
            ->get();

        return view('student.stuform.formSubmissionStu', ['submissionPost' => $submissionPost, 'formSubmissions' => $formSubmissions]);
    }

    public function createFormSubmission()
    {
        // return view('admin.adminpage.createform');
        return view('admin.template_page.newcreatetets');

    }
}
