<?php

namespace App\Http\Controllers\Lecturer;

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

        return view('lecturer.slide_page.allSlidesTemplate',compact('template'));
    }

    public function create()
    {
        return view('lecturer.slide_page.createSlidesTemplate');
    }

    public function edit( $id)
    {
        $data['getRecord'] = Template::getSingle($id);
        if(!empty($data['getRecord'])){
            return view('lecturer.slide_page.editSlidesTemplate',$data);
        }
        else{
            abort(404);
        }
    }

    // ---------------------------------------------------------

    public function indexPost()
    {

        $post = SubmissionPost::getLecturerSLideSP();
        // dd( $post);
        // Calculate remaining time for each submission
        foreach ($post as $postform) {
            $postform->remainingTime = $this->calculateRemainingTime($postform->submission_deadline);
        }

        return view('lecturer.slide_page.allslidespost',compact('post'));

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
        return view('lecturer.slide_page.create_slideS');
    }

    public function editPost($id)
    {
        $data['getRecord'] = SubmissionPost::getSingle($id);

        if(!empty($data['getRecord'])){
            // dd($data);
            return view('lecturer.slide_page.editSlidesF', $data);
        }
        else{
            return redirect()->route('lectslidespost.index')->with('error', 'Submission post not found');
        }
    }

    public function getSlidesSubmissionForLecturer($submissionPostId){
        $user = auth()->user(); // Get the currently logged-in lecturer
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
            // Fetch form submissions for the specific submission post and the lecturer's students
            $slideSubmissions = Slide_submission::where('submission_post_id', $submissionPost->id)
            ->whereHas('student', function ($query) use ($user) {
                $query->where('supervisor_id', $user->id);
            })
            ->get();

            // Fetch all students supervised by the current lecturer
            // $students = DB::table('students')
            // ->select('students.stu_id', 'users.name AS student_name', 'students.matric_number')
            // ->leftJoin('users', 'students.stu_id', '=', 'users.id')
            // ->leftJoin('slide_submissions', function ($join) use ($user) {
            //     $join->on('students.stu_id', '=', 'slide_submissions.student_id')
            //         // ->where('form_submissions.submission_post_id', $submissionPostId) // If needed, add this line to filter by submission_post_id
            //         ->where('students.supervisor_id', $user->id);
            // })
            // ->whereNull('slide_submissions.student_id') // Add this line to filter students without submissions
            // ->get();
            $students = DB::table('students')
            ->select('students.stu_id', 'users.name AS student_name', 'students.matric_number')
            ->leftJoin('users', 'students.stu_id', '=', 'users.id')
            ->leftJoin('slide_submissions', 'students.stu_id', '=', 'slide_submissions.student_id')
            ->where('students.supervisor_id', $user->id)
            ->distinct()
            ->get();
            // dd($students);

            return view('lecturer.slide_page.viewAllSlides', compact('slideSubmissions','students','submissionPost'));
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

        return view('lecturer.slide_page.viewOneSlide', compact('slideSubmission','submissionPostId', 'comments'));
    }
    public function removeFile(Request $request, $submissionPostId)
    {
        // Validate the request
        $request->validate([
            'path' => 'required|string', // Assuming 'path' is the key sent in the AJAX request
        ]);
        // Find the form submission record
        $submission_post = SubmissionPost::findOrFail($submissionPostId);

        // Decode the form_files JSON
        $formFiles = json_decode($submission_post->files, true);

        // Find the index of the file to be removed
        $indexToRemove = array_search($request->input('path'), array_column($formFiles, 'path'));

        if ($indexToRemove !== false) {
            // Remove the file from the array
            array_splice($formFiles, $indexToRemove, 1);

            // Encode the updated form_files array back to JSON
            $submission_post->files = json_encode($formFiles);

            // Save the updated form submission record
            $submission_post->save();

            return response()->json(['message' => 'File removed successfully']);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }
}
