<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\SubmissionPost;
use App\Models\Student;
use App\Models\Comment;
use App\Models\Proposal_submission;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// reminder
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

//filevalidation
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ProposalController extends Controller
{
    public function index()
    {
        $loggedInUser = Auth::user();

        if ($loggedInUser->role_as == 0) {
            $template = Template::getAdminProposalTemplate();

        } else {
            $template = Template::getLecturerProposalTemplate();
        }

        return view('lecturer.report_page.proposal.allProposalTemplate',compact('template'));
    }

    public function create()
    {
        return view('lecturer.report_page.proposal.createProposalTemplate');

    }

    public function edit( $id)
    {
        $data['getRecord'] = Template::getSingle($id);
        if(!empty($data['getRecord'])){
            return view('lecturer.report_page.proposal.editProposalTemplate',$data);
        }
        else{
            abort(404);
        }
    }

    // store and update use same method.
    // ============================================================================

    public function indexPost()
    {

        $loggedInUser = Auth::user();

        if ($loggedInUser->role_as == 0) {
            $post = SubmissionPost::getAdminProposalSP();

            // Calculate remaining time for each submission
            foreach ($post as $postform) {
                $postform->remainingTime = $this->calculateRemainingTime($postform->submission_deadline);
            }

            return view('admin.report_page.proposal.allproposalpost',compact('post'));

        } else {
            $post = SubmissionPost::getLecturerProposalSP();

            // Calculate remaining time for each submission
            foreach ($post as $postform) {
                $postform->remainingTime = $this->calculateRemainingTime($postform->submission_deadline);
            }

            return view('lecturer.report_page.proposal.allproposalpost',compact('post'));
        }
        return view('lecturer.report_page.proposal.allformpost',compact('post'));

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
        return view('lecturer.report_page.proposal.create_proposalS');
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
            'section' => "proposal",
        ]);
        return redirect()->route('lectproposalpost.index')->with('success','Submission post created successfully');
    }

    // update,store and delete POST same as Form controller

    public function getProposalSubmissionForLecturer($submissionPostId){
        // $lecturer = auth()->user(); // Get the currently logged-in lecturer
        // $submissionPost = SubmissionPost::with('lecturer')->find($submissionPostId);
        // dd(auth()->user());
        $user = auth()->user();
        $submissionPost = SubmissionPost::with('lecturer')->find($submissionPostId);

        // added accesible for admin role
        if ($user->role_as == 0) {

            $proposalSubmissions = Proposal_submission::where('submission_post_id', $submissionPost->id)
            ->get();
            // dd( $proposalSubmissions);

            $students = DB::table('students')
            ->select('students.stu_id', 'users.name AS student_name', 'students.matric_number', 'proposal_submissions.*')
            ->leftJoin('users', 'students.stu_id', '=', 'users.id')
            ->leftJoin('proposal_submissions', 'students.stu_id', '=', 'proposal_submissions.student_id')
            ->get();
            // dd( $students);
            return view('admin.report_page.proposal.viewAll', compact('proposalSubmissions','students','submissionPost'));

        }
        elseif(auth()->user()->role_as == 1){

            $proposalSubmissions = Proposal_submission::where('submission_post_id', $submissionPost->id)
            ->whereHas('student', function ($query) use ($user) {
                $query->where('supervisor_id', $user->id);
            })
            ->get();

            // // Fetch all students supervised by the current lecturer
            // $students = DB::table('students')
            // ->select('students.stu_id', 'users.name AS student_name', 'students.matric_number')
            // ->leftJoin('users', 'students.stu_id', '=', 'users.id')
            // ->leftJoin('proposal_submissions', function ($join) use ($user) {
            //     $join->on('students.stu_id', '=', 'proposal_submissions.student_id')
            //         // ->where('form_submissions.submission_post_id', $submissionPostId) // If needed, add this line to filter by submission_post_id
            //         ->where('students.supervisor_id', $user->id);
            // })
            // ->whereNull('proposal_submissions.student_id') // Add this line to filter students without submissions
            // ->get();
            $students = DB::table('students')
            ->select('students.stu_id', 'users.name AS student_name', 'students.matric_number')
            ->leftJoin('users', 'students.stu_id', '=', 'users.id')
            ->leftJoin('proposal_submissions', 'students.stu_id', '=', 'proposal_submissions.student_id')
            ->where('students.supervisor_id', $user->id)
            ->distinct()
            ->get();
            // dd($student/s);

            return view('lecturer.report_page.proposal.viewAllProposal', compact('proposalSubmissions','students','submissionPost'));
        }

    }

    public function showProposalSubmissions($proposalSubmissionId)

    {
        // $submissionPost = SubmissionPost::findOrFail($submissionPostId);
        $proposalSubmission = Proposal_submission::findOrFail($proposalSubmissionId);
        // $student = Student::findOrFail($studentId);
        $submissionPostId = $proposalSubmission->submissionPost->id;
        $comments = Comment::where('commentable_id', $proposalSubmissionId)
        ->where(function ($query) use ($proposalSubmission) {
            // Include comments made by the student
            $query->where('lecturer_id', Auth::id())
                // Include comments made by the lecturer who supervises the student
                ->orWhere('student_id', $proposalSubmission->student_id);
        })
        ->get();
        $allcomments = $proposalSubmission->comments;

        return view('lecturer.report_page.proposal.viewOneProposal', compact('proposalSubmission','submissionPostId', 'comments'));
    }

    // $submissionPostId
    public function updateStatus(Request $request, Thesis_submission $submission)
    // public function updateStatus(Request $request, $submissionPostId)
    {
        $request->validate([
            'thesis_status' => 'required|in:pending,approved,rejected',
        ]);

        $submission->update([
            'thesis_status' => $request->input('thesis_status'),
        ]);

        $studentName = $submission->student->user->name;
        $thesis_status = $submission->thesis_status;

        return redirect()->back()->with('success', "Thesis status for $studentName updated successfully as '$thesis_status'.");
    }

    public function editPost($id)
    {
        $data['getRecord'] = SubmissionPost::getSingle($id);

        if(!empty($data['getRecord'])){
            // dd($data);
            return view('lecturer.report_page.proposal.editProposalF', $data);
        }
        else{
            return redirect()->route('lectproposalpost.index')->with('error', 'Submission post not found');
        }
    }}
