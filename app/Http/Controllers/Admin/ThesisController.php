<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\SubmissionPost;
use App\Models\Student;
use App\Models\Comment;
use App\Models\Thesis_submission;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// reminder
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

//filevalidation
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ThesisController extends Controller
{

    public function index()
    {
        $loggedInUser = Auth::user();

        if ($loggedInUser->role_as == 0) {
            $template = Template::getAdminThesisTemplate();

        } else {
            $template = Template::getLecturerThesisTemplate();
        }

        return view('admin.report_page.allThesisTemplate',compact('template'));
    }

    public function create()
    {
        return view('admin.report_page.createThesisTemplate');

    }

    public function edit( $id)
    {
        $data['getRecord'] = Template::getSingle($id);
        if(!empty($data['getRecord'])){
            return view('admin.report_page.editThesisTemplate',$data);
        }
        else{
            abort(404);
        }
    }

    // store and update use same method.
    // ============================================================================
    public function indexPost()
    {

        // define nem of varibale then use back
        // $template = Template::latest()->paginate(3);
        $post = SubmissionPost::getAdminThesisSP();
        // dd( $post);
        // Calculate remaining time for each submission
        foreach ($post as $postform) {
            $postform->remainingTime = $this->calculateRemainingTime($postform->submission_deadline);
        }

        return view('admin.report_page.allthesispost',compact('post'));

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
        return view('admin.report_page.create_thesisS');
    }

    public function storePost(Request $request)
    {
        // $data =
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'submission_deadline' => 'required|date',
            'files.*' => 'mimes:pdf,doc,docx',
            'visibility_status' => 'required|in:0,1',
        ]);

        $filePaths = [];

        // before on without the uplaoded_at
        // Handle file uploads and store them
        // if ($request->hasFile('files')) {
        //     foreach ($request->file('files') as $file) {
        //         $filename = $file->getClientOriginalName();
        //         // Store the file in the public/upload/templates directory
        //         $file->storeAs('public/upload/submissionpost', $filename);
        //         $filePaths[] = 'upload/submissionpost/' . $filename; // Store the file path
        //     }
        // }
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = $file->getClientOriginalName();
                // Store the file in the public/upload/templates directory
                // $file->storeAs('public/upload/templates', $filename);
                // $filePaths[] = 'upload/templates/' . $filename; // Store the file path
                $extension = $file->getClientOriginalExtension();
                // $mime_type = $this->getMimeType($extension);

                // Get the current logged-in user's ID
                $userId = auth()->user()->id;
                // Get the current time
                $currentTime = now();

                // Format the timestamp (optional, adjust as needed)
                $formattedTimestamp = $currentTime->format('Ymd_His');

                // Append the user's ID to the file path
                // $userFilePath = 'upload/templates/user_' . $userId;

                // Append the user's ID and timestamp to the file path
                $userFilePath = "upload/submission_post/user_{$userId}";

                // Store the file in the public/upload/templates/user_{user_id} directory
                $file->storeAs("public/$userFilePath", $filename);

                $filePaths[] = [
                    'path' => "$userFilePath/$filename",
                    'uploaded_at' => $currentTime,
                ];
            }
        }

        SubmissionPost::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'submission_deadline'=> $request->input('submission_deadline'),
            'files' => json_encode($filePaths),
            'visibility_status' => $request->input('visibility_status'),
            'lecturer_id' => Auth::id(),
            'section' => "thesis",
        ]);
        return redirect()->route('proposalpost.index')->with('success','Submission post created successfully');
    }

    public function updatePost(Request $request, $id)
    {
        try{

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'submission_deadline' => 'required|date',
            'files.*' => [
                'file',
                'mimes:pdf,doc,docx',
                'max:2048',
            ],
        ], [
            'files.*.file' => 'Invalid file format.',
            'files.*.mimes' => 'Allowed file types are pdf, doc, and docx.',
            'files.*.unique' => 'A file with the same name already exists.',
        ]);

        $post = SubmissionPost::find($id);


        // Use the validateFiles method to check for duplicate file names
        // $validation = $this->validateFiles($request->file('files'), $id);
        // if ($validation->fails()) {
        //     return redirect()->back()->withErrors($validation)->withInput();
        // }

        $filePaths = [];

        if ($request->hasFile('files')) {
            $newFiles = $request->file('files');
            $existingFiles = json_decode($post->files, true) ?? [];
            // dd($newFiles);

            foreach ($newFiles as $file) {
                $filename = $file->getClientOriginalName();

                // Get the current logged-in user's ID
                $userId = auth()->user()->id;
                // Get the current time
                $currentTime = now();

                $userFilePath = "upload/submission_post/user_{$userId}";
                $file->storeAs("public/$userFilePath", $filename);
                // $filePaths[] = 'upload/templates/' . $filename;
                // dd($filePaths[]);
                // Store the file path and upload time in the database
                $filePaths[] = [
                    'path' => "$userFilePath/$filename",
                    'uploaded_at' => $currentTime, // Get the current time
                ];
            }
            $existingFiles = json_decode($post->files, true) ?? [];
            $existingFiles = array_merge($existingFiles, $filePaths);

            $post->files = json_encode($existingFiles);
        }

        // Update other fields (e.g., title, description) as needed
        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->submission_deadline = $request->input('submission_deadline');

        // Save the updated record
        $post->save();

        // Redirect or return a response
        return redirect()->route('formpost.index')->with('success','FORM submission post updated successfully');
        } catch (\Exception $e) {
            // Handle exceptions, such as ModelNotFoundException or file storage errors
            return redirect()->route('formpost.index')->with('error', 'An error occurred while updating the FORM submission post');
        }
    }

    public function destroyPost($id)
    {
        $postform = SubmissionPost::find($id);

        if (!$postform) {
            return redirect()->route('thesispost.index')->with('error', 'THESIS submission post not found');
        }

        $postform->delete();

        return redirect()->route('thesispost.index')->with('success', 'THESIS submission post deleted successfully');
    }

    // ---------------------------------------------------------------------
    public function getThesisSubmissionForLecturer($submissionPostId){
        $lecturer = auth()->user(); // Get the currently logged-in lecturer
        // $submissionPost = SubmissionPost::find($submissionPostId);
        $submissionPost = SubmissionPost::with('lecturer')->find($submissionPostId);

        // added accesible for admin role
        if (auth()->user()->role_as == 0) {

            // dd($submissionPost);

            $thesisSubmissions = Thesis_submission::where('submission_post_id', $submissionPost->id)
            ->get();
            $submissionPostID = $submissionPost->id;
            $students = DB::table('students')
            ->select('students.stu_id', 'users.name AS student_name', 'students.matric_number', 'thesis_submissions.*')
            ->leftJoin('users', 'students.stu_id', '=', 'users.id')
            ->leftJoin('thesis_submissions', function ($join) use ($submissionPostID) {
                $join->on('students.stu_id', '=', 'thesis_submissions.student_id')
                    ->where('thesis_submissions.submission_post_id', $submissionPostID);
            })
            ->get();

            // dd( $students);
            $statuses = ['approved' => 'Approved', 'rejected' => 'Rejected', 'pending' => 'Pending'];

            return view('admin.report_page.viewAllThesis', compact('thesisSubmissions','students','submissionPost', 'statuses'));

        }
        elseif(auth()->user()->role_as == 1){
            if (($submissionPost && $submissionPost->lecturer && $submissionPost->lecturer->id === $lecturer->id)) {

            // NEED TO ADD ONE PART, if lecture role is admin, access all student instead of joinging.
            // if its lecturer, asses the one she created and access all the admin.
            $thesisSubmissions = $lecturer->formSubmissions()
            ->where('submission_post_id', $submissionPost->id)
            ->get();

            $thesisSubmissions = Thesis_submission::where('submission_post_id', $submissionPost->id)
            ->get();

            // ADMIN
            // $students = DB::table('students')
            // ->select('students.stu_id', 'users.name AS student_name', 'students.matric_number', 'form_submissions.*')
            // ->leftJoin('users', 'students.stu_id', '=', 'users.id')
            // ->leftJoin('form_submissions', 'students.stu_id', '=', 'form_submissions.student_id')
            // ->get();
            // dd($students);

            // THIS IS FOR LECTURER INTERFACE!! ONLY SUPERVISED STUDENTS
            $students = DB::table('students')
            ->select('students.stu_id', 'users.name AS student_name', 'students.matric_number')
            ->leftJoin('users', 'students.stu_id', '=', 'users.id')
            ->leftJoin('thesis_submissions', function ($join) use ($lecturer, $submissionPost) {
                $join->on('students.stu_id', '=', 'thesis_submissions.student_id')
                    ->where('thesis_submissions.submission_post_id', $submissionPost->id);
            })
            ->where(function ($query) use ($lecturer) {
                $query->where('students.supervisor_id', $lecturer->id)
                    ->orWhereNull('thesis_submissions.student_id');
            })
            ->get();
            $statuses = ['approved' => 'Approved', 'rejected' => 'Rejected', 'pending' => 'Pending'];

            return view('admin.report_page.viewAllThesis', compact('thesisSubmissions','students','submissionPost','statuses'));
        }

        }
    }

    public function showThesisSubmissions($thesisSubmissionId)

    {
        // $submissionPost = SubmissionPost::findOrFail($submissionPostId);
        $thesisSubmission = Thesis_submission::findOrFail($thesisSubmissionId);
        // dd($thesisSubmission);
        // $student = Student::findOrFail($studentId);
        $submissionPostId = $thesisSubmission->submissionPost->id;
        // dd($submissionPostId);

        $comments = Comment::where('commentable_id', $thesisSubmissionId)
        ->where(function ($query) use ($thesisSubmission) {
            // Include comments made by the student
            $query->where('lecturer_id', Auth::id())
                // Include comments made by the lecturer who supervises the student
                ->orWhere('student_id', $thesisSubmission->student_id);
        })
        ->get();
        // dd($comments);

        $allcomments = $thesisSubmission->comments;
        // dd($allcomments);

        return view('admin.report_page.viewOne', compact( 'thesisSubmission','submissionPostId', 'comments'));
    }

    // $submissionPostId
    // public function updateStatus(Request $request, Thesis_submission $submission)
    // public function updateStatus(Request $request, $submissionPostId)

    // public function updateStatus(Request $request, ThesisSubmission $thesisSubmission)
    // {
    //     dd($thesisSubmission);

    //     $request->validate([
    //         'thesis_status' => 'required|in:pending,approved,rejected',
    //     ]);

    //     $submission->update([
    //         'thesis_status' => $request->input('thesis_status'),
    //     ]);

    //     // $studentName = $submission->student->user->name;
    //     $studentName = $submission->user->name;

    //     $thesis_status = $submission->thesis_status;

    //     return redirect()->back()->with('success', "Thesis status for $studentName updated successfully as '$thesis_status'.");
    // }

    public function updateStatus($studentId, Request $request) {
        // Validate request if needed
        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
        ]);

        // Update the thesis status in the database
        $student = Student::find($studentId);
        $thesisSubmission = $student->thesisSubmission;
        // dd($thesisSubmission);

        // $thesisSubmission->thesis_status = $request->input('status');
        // $thesisSubmission->save();

        // Loop through each student and update thesis status
        foreach ($thesisSubmission as $thesis) {
            $thesis->update(['thesis_status' => $request->input('status')]);
        }
        // Redirect back to the view
        return redirect()->back()->with('success', 'Thesis status updated successfully.');
    }

    public function editPost($id)
    {
        $data['getRecord'] = SubmissionPost::getSingle($id);

        if(!empty($data['getRecord'])){
            return view('admin.report_page.editThesisF', $data);
        }
        else{
            return redirect()->route('thesispost.index')->with('error', 'Submission post not found');
        }
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
