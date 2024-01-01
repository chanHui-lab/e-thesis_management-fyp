<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Models\Template;
use App\Models\SubmissionPost;
use App\Models\Student;
use App\Models\Slide_submission;
use App\Models\Comment;

class SlideController extends Controller
{
    public function index()
    {

        $template = Template::getStuSlideTemplate();
        $user = Auth::user();

        $slidepost = SubmissionPost::with('lecturer')
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
        ->where('section', 'slide')
        ->get();

        $submissionDetails = [];

        foreach ($slidepost as $submissionPost) {
            $slideSubmission = $submissionPost->slideSubmissions()
                ->where('student_id', $user->id)
                ->first();

        if ($slideSubmission) {
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
            'slideSubmission' => $slideSubmission,
            'status' => $status,
            'chipClass' => $chipClass,
        ];
    }
        return view('student.stuslide.slideTemplate&Submission',compact('template','submissionDetails','slidepost'));
    }

    public function showStuSlideSubmissionDetails($submissionPostId){
        // Fetch the submission post details
        $submissionPost = SubmissionPost::where('id', $submissionPostId)->first();

        if (!$submissionPost) {
            abort(404);
        }

        // Fetch form submissions related to the submission post for the current student
        $slideSubmission = $submissionPost->slideSubmissions()
            ->where('student_id', Auth::id())
            ->first();
        // dd($thesisSubmissions);
        // Call the method to get form submission details along with comments
        // return $this->showFormSubmission($formSubmission->id,$submissionPost);
        // return $this->showFormSubmission(optional($formSubmission)->id, $submissionPost);
        // Check if $formSubmission is not null
        if ($slideSubmission) {
            // Use the obtained formSubmission in your logic
            return $this->showSlideSubmission($slideSubmission->id, $submissionPost);
        } else {
            // Redirect to a blade view or perform any other action as needed
            return view('student.stuslide.slideSubmissionStu',
            ['submissionPost' => $submissionPost ,
                 'slideSubmission' => null,
        ]);
        }
    }
    public function showSlideSubmission($slideSubmissionId,$submissionPost)
    {
        // $formSubmission = Form_submission::with('comments.student.user')->findOrFail($formSubmissionId);
        $slideSubmission = Slide_submission::findOrFail($slideSubmissionId);
        // $comments = $formSubmission->comments;
        $comments = Comment::where('commentable_id', $slideSubmission->id)
        ->where(function ($query) use ($slideSubmission) {
            // Include comments made by the student
            $query->where('student_id', Auth::id())
                // Include comments made by the lecturer who supervises the student
                ->orWhere('lecturer_id', $slideSubmission->supervisor_id);
        })
        ->get();
        // dd($comments);
        $allcomments = $slideSubmission->comments;
        // dd($allcomments);
        // dd($thesisSubmission);

        return view('student.stuslide.slideSubmissionStu', compact('submissionPost','slideSubmission', 'comments'));
    }
    public function createSlideSubmission($submissionPostId)
    {
        return view('student.stuslide.slideSubCreate', compact('submissionPostId'));
    }

    public function storeSlideSubmission(Request $request)
    {
        try{

        $request->validate([
            'slide_title' => 'required',
            'slide_description' => 'required',
            'slide_file.*' => 'required|file|mimes:pdf', // Adjust max file size as needed
            ]);

        $submissionPostId = $request->input('submission_post_id');

        $filePaths = [];

        // Handle file uploads and store them
        if ($request->hasFile('slide_file')) {
            foreach ($request->file('slide_file') as $file) {
                $filename = $file->getClientOriginalName();
                $userId = auth()->user()->id;
                $currentTime = now();
                $formattedTimestamp = $currentTime->format('Ymd_His');
                // Store the file in the public/upload/templates directory
                $userFilePath = "upload/slideSubmission/stu_{$userId}/{$formattedTimestamp}";
                $file->storeAs("public/$userFilePath", $filename);
                // $request->file('proposal_file')->storeAs("public/$userFilePath", $filename);
                $filePaths[] = [
                    'path' => "$userFilePath/$filename",
                    'uploaded_at' => $currentTime,
                ];
            }
        }
        // dd($filePaths);

        $student = Auth::user()->student; // Assuming Auth::user() is a student
        $supervisor = $student->supervisor;

        if ($supervisor) {
            $supervisorId = $supervisor->lecturer_id;

        } else {
            // dd($student);
        }

        Slide_submission::create([
            'slide_title' => $request->input('slide_title'),
            'slide_description' => $request->input('slide_description'),
            'slide_file' => json_encode($filePaths), //its actually actual path
            'student_id'=> Auth::id(),
            'supervisor_id' => $supervisorId,
            'submission_post_id' => $submissionPostId,
            'sem_id' => 2,
        ]);

        // dd($submissionPostId);

        return redirect()->route('stuSlideSubmission.details',  ['submissionPostId' => $submissionPostId])->with('successUpdate', 'Slides submission store successfully.');

        }
        catch(\Exception $e){
            // return redirect()->route('stuFormSubmission.details',['formSubmissionId' => $formSubmission->id])->with('error', 'An error occurred while updating the template');
            return response()->json(['error' => 'An error occurred while updating the template: ' . $e->getMessage()], 500);
            return redirect()->route('stuSlideSubmission.details', ['submissionPostId' => $submissionPostId])->with('errorUpdate', 'Some errors occur to store Slides submission.');

        }
    }

    public function editSlideSubmission($slideSubmissionId,$submissionPostId)
    {
        $data['getRecord'] = Slide_submission::getSingle($slideSubmissionId);
        // Return the view for editing with the form submission data
        return view('student.stuslide.slideSubEdit', $data, compact('submissionPostId'));
    }

    public function updateSlideSubmission(Request $request, $slideSubmissionId)
    {

        // Fetch the form submission by ID
        $slideSubmission = Slide_submission::find($slideSubmissionId);

        try{
        // dd($request->all());
        $request->validate([
            'slide_title' => 'required',
            'slide_file.*' => 'nullable|mimes:pdf',
        ]);
        // dd($request->all());

        $filePaths = [];
        if ($request->hasFile('slide_file')) {
            $newFiles = $request->file('slide_file');
            $existingFiles = json_decode($slideSubmission->slide_file, true) ?? [];

            foreach ($request->file('slide_file') as $file) {
                // dd($file);

                $userId = auth()->user()->id;
                $currentTime = now();
                $formattedTimestamp = $currentTime->format('Ymd_His');
                // Store the file in the public/upload/templates directory
                $userFilePath = "upload/slideSubmission/stu_{$userId}/{$formattedTimestamp}";
                $filename = $file->getClientOriginalName();

                $file->storeAs("public/$userFilePath", $filename);

                $filePaths[] = [
                    'path' => "$userFilePath/$filename",
                    'uploaded_at' => $currentTime,
                ];

                $existingFiles[] = [
                    'path' => "$userFilePath/$filename",
                    'uploaded_at' => $currentTime,
                ];
            }
            // dd($filePaths);

            $existingFiles = json_decode($slideSubmission->slide_file, true) ?? [];
            $existingFiles = array_merge($existingFiles, $filePaths);
            // dd($existingFiles);

            $slideSubmission->slide_file = json_encode($existingFiles);
        }

        // Update other fields (e.g., title, description) as needed
        $slideSubmission->slide_title = $request->input('slide_title');
        $slideSubmission->slide_description = $request->input('slide_description');
        // Save the updated record
        $slideSubmission->save();
        // dd($slideSubmission);

        $submissionPostId = $slideSubmission->submission_post_id;
        // Redirect the user to the view page or any other appropriate route
        // return redirect()->route('stuFormSubmission.details')->with('success', 'Form submission updated successfully.');
        return redirect()->route('stuSlideSubmission.details', ['submissionPostId' => $submissionPostId])->with('successUpdate', 'Slide submission updated successfully.');

        }
        catch(\Exception $e){
            // return redirect()->route('stuFormSubmission.details',['formSubmissionId' => $formSubmission->id])->with('error', 'An error occurred while updating the template');
            // return response()->json(['error' => 'An error occurred while updating the template: ' . $e->getMessage()], 500);
            return redirect()->route('stuSlideSubmission.details', ['submissionPostId' => $submissionPostId])->with('errorUpdate', 'Some errors occur to update Slide submission.');

        }

    }
    public function deleteSlideSubmission($slideSubmissionId)
    {
        $proposalSubmission = Slide_submission::find($slideSubmissionId);
        // dd($thesisSubmission);

        if (!$proposalSubmission) {
            return redirect()->back()->with('error', 'Slide submission not found.');
        }

        // Perform any additional checks (e.g., user authorization) before deletion
        $proposalSubmission->delete();

        return redirect()->back()->with('successDelete', 'Slide submission deleted successfully.');
    }
    public function removeFile(Request $request, $slideSubmissionId)
    {
        // Validate the request
        $request->validate([
            'path' => 'required|string', // Assuming 'path' is the key sent in the AJAX request
        ]);
        // Find the form submission record
        $formSubmission = Slide_submission::findOrFail($slideSubmissionId);

        // Decode the form_files JSON
        $formFiles = json_decode($formSubmission->slide_file, true);
        // dd($formFiles);

        // Find the index of the file to be removed
        $indexToRemove = array_search($request->input('path'), array_column($formFiles, 'path'));

        if ($indexToRemove !== false) {
            // Remove the file from the array
            array_splice($formFiles, $indexToRemove, 1);

            // Encode the updated form_files array back to JSON
            $formSubmission->slide_file = json_encode($formFiles);

            // Save the updated form submission record
            $formSubmission->save();

            return response()->json(['message' => 'File removed successfully']);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }
}
