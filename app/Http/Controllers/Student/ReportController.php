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
use App\Models\Proposal_submission;
use App\Models\Comment;
use App\Models\Thesis_submission;

class ReportController extends Controller
{
    public function index()
    {

        $template = Template::getStuProposalTemplate();
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
            ->where('section', 'proposal')
            ->get();

        $proposalpost = SubmissionPost::with('lecturer')
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
        ->where('section', 'proposal')
        ->get();

        $submissionDetails = [];

        foreach ($proposalpost as $submissionPost) {
            $formSubmission = $submissionPost->proposalSubmissions()
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
        return view('student.stureport.proposalTemplate&Submission',compact('template','submissionDetails','proposalpost'));
    }

    public function showStuProposalSubmissionDetails($submissionPostId){
        // Fetch the submission post details
        $submissionPost = SubmissionPost::where('id', $submissionPostId)->first();

        if (!$submissionPost) {
            abort(404);
        }

        // Fetch form submissions related to the submission post for the current student
        $proposalSubmission = $submissionPost->proposalSubmissions()
            ->where('student_id', Auth::id())
            ->first();
        // dd($thesisSubmissions);
        // Call the method to get form submission details along with comments
        // return $this->showFormSubmission($formSubmission->id,$submissionPost);
        // return $this->showFormSubmission(optional($formSubmission)->id, $submissionPost);
        // Check if $formSubmission is not null
        if ($proposalSubmission) {
            // Use the obtained formSubmission in your logic
            return $this->showProposalSubmission($proposalSubmission->id, $submissionPost);
        } else {
            // Redirect to a blade view or perform any other action as needed
            return view('student.stureport.proposalSubmissionStu',
            ['submissionPost' => $submissionPost ,
                 'proposalSubmission' => null,
        ]);
        }
    }

    public function createProposalSubmission($submissionPostId)
    {
        return view('student.stureport.proposalSubCreate', compact('submissionPostId'));
    }

    public function storeProposalSubmission(Request $request)
    {
        // dd($request->all());
        try{
        $request->validate([
            'proposal_title' => 'required',
            'proposal_description' => 'required',
            'proposal_file.*' => 'required|file|mimes:pdf', // Adjust max file size as needed
            'proposal_type' => 'array', // Ensures it's an array
            'proposal_type.*' => 'in:web_development,machine_learning,data_analytics,mobile_development', // Adjust options as needed
        ]);


        $submissionPostId = $request->input('submission_post_id');

        $filePaths = [];

        // Handle file uploads and store them
        if ($request->hasFile('proposal_file')) {
            foreach ($request->file('proposal_file') as $file) {
                $filename = $file->getClientOriginalName();
                $userId = auth()->user()->id;
                $currentTime = now();
                $formattedTimestamp = $currentTime->format('Ymd_His');
                // Store the file in the public/upload/templates directory
                $userFilePath = "upload/proposal_sub/stu_{$userId}/{$formattedTimestamp}";
                $file->storeAs("public/$userFilePath", $filename);
                // $request->file('proposal_file')->storeAs("public/$userFilePath", $filename);
                $filePaths[] = [
                    'path' => "$userFilePath/$filename",
                    'uploaded_at' => $currentTime,
                ];
            }
        }

        $student = Auth::user()->student; // Assuming Auth::user() is a student
        $supervisor = $student->supervisor;

        if ($supervisor) {
            $supervisorId = $supervisor->lecturer_id;

        } else {
            // dd($student);
        }

        Proposal_submission::create([
            'proposal_title' => $request->input('proposal_title'),
            'proposal_description' => $request->input('proposal_description'),
            'proposal_file' => json_encode($filePaths), //its actually actual path
            'proposal_type' => implode(',', $request->input('proposal_type')), // Convert array to string
            'student_id'=> Auth::id(),
            'supervisor_id' => $supervisorId,
            'submission_post_id' => $submissionPostId,
            'sem_id' => 2,
        ]);

        return redirect()->route('stuProposalSubmission.details', ['submissionPostId' => $submissionPostId])->with('successUpdate', 'Proposal submission store successfully.');

        }
        catch(\Exception $e){
            // return redirect()->route('stuFormSubmission.details',['formSubmissionId' => $formSubmission->id])->with('error', 'An error occurred while updating the template');
            // return response()->json(['error' => 'An error occurred while updating the template: ' . $e->getMessage()], 500);
            return redirect()->route('stuProposalSubmission.details', ['submissionPostId' => $submissionPostId])->with('errorUpdate', 'Some errors occur to store Proposal submission.');

        }
    }

    public function showProposalSubmission($proposalSubmissionId,$submissionPost)
    {
        // $formSubmission = Form_submission::with('comments.student.user')->findOrFail($formSubmissionId);
        $proposalSubmission = Proposal_submission::findOrFail($proposalSubmissionId);
        // $comments = $formSubmission->comments;
        $comments = Comment::where('commentable_id', $proposalSubmission->id)
        ->where(function ($query) use ($proposalSubmission) {
            // Include comments made by the student
            $query->where('student_id', Auth::id())
                // Include comments made by the lecturer who supervises the student
                ->orWhere('lecturer_id', $proposalSubmission->supervisor_id);
        })
        ->get();
        // dd($comments);
        $allcomments = $proposalSubmission->comments;
        // dd($allcomments);
        // dd($thesisSubmission);

        return view('student.stureport.proposalSubmissionStu', compact('submissionPost','proposalSubmission', 'comments'));
    }

    public function editProposalSubmission($proposalSubmissionId,$submissionPostId)
    {
        $data['getRecord'] = Proposal_submission::getSingle($proposalSubmissionId);
        // dd($data['getRecord']);

        // Assuming 'thesis_type' is a comma-separated string in the database
        $selectedProposalTypes = explode(',', $data['getRecord']->proposal_type);

        $proposalTypes = [
            'web_development',
            'mobile_development',
            'machine_learning',
            'data_analytics',
            // Add more types as needed
        ];
        // Return the view for editing with the form submission data
        return view('student.stureport.proposalSubEdit', $data, compact('selectedProposalTypes', 'proposalTypes', 'submissionPostId'));
    }

    public function updateProposalSubmission(Request $request, $proposalSubmissionId)
    {

        // Fetch the form submission by ID
        $proposalSubmission = Proposal_submission::find($proposalSubmissionId);

        try{
        // dd($request->all());
        $request->validate([
            'proposal_title' => 'required',
            'proposal_file.*' => 'nullable|mimes:pdf',
            'proposal_type' => 'array', // Ensures it's an array
            'proposal_type.*' => 'in:web_development,machine_learning,data_analytics,mobile_development', // Adjust options as needed
        ]);

        $filePaths = [];
        if ($request->hasFile('proposal_file')) {
            $newFiles = $request->file('proposal_file');
            $existingFiles = json_decode($proposalSubmission->proposal_file, true) ?? [];

            foreach ($request->file('proposal_file') as $file) {
                // dd($file);

                $userId = auth()->user()->id;
                $currentTime = now();
                $formattedTimestamp = $currentTime->format('Ymd_His');
                // Store the file in the public/upload/templates directory
                $userFilePath = "upload/proposalSubmission/stu_{$userId}/{$formattedTimestamp}";
                $filename = $file->getClientOriginalName();

                // $existingFilenames = array_column($existingFiles, 'path');
                // if (in_array("$userFilePath/$filename", $existingFilenames)) {
                //     return redirect()->back()->with('error', 'Duplicate filename found: ' . "$userFilePath/$filename");
                // }

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

            $existingFiles = json_decode($proposalSubmission->proposal_file, true) ?? [];
            $existingFiles = array_merge($existingFiles, $filePaths);
            // dd($existingFiles);

            $proposalSubmission->proposal_file = json_encode($existingFiles);
        }

        // Update the form submission with new data from the request
        // $formSubmission->update([
        //     'form_title' => $request->input('form_title'),
        //     'description' =>$request->input('description'),
        //     // Add other fields as needed
        // ]);
        // Update other fields (e.g., title, description) as needed
        $proposalSubmission->proposal_title = $request->input('proposal_title');
        $proposalSubmission->proposal_description = $request->input('proposal_description');
        $thesisTypes = implode(',', $request->input('proposal_type'));
        $proposalSubmission->proposal_type = $thesisTypes;
        // Save the updated record
        $proposalSubmission->save();
        // dd($proposalSubmission);

        $submissionPostId = $proposalSubmission->submission_post_id;
        // Redirect the user to the view page or any other appropriate route
        // return redirect()->route('stuFormSubmission.details')->with('success', 'Form submission updated successfully.');
        return redirect()->route('stuProposalSubmission.details', ['submissionPostId' => $submissionPostId])->with('successUpdate', 'Proposal submission updated successfully.');

        }
        catch(\Exception $e){
            // return redirect()->route('stuFormSubmission.details',['formSubmissionId' => $formSubmission->id])->with('error', 'An error occurred while updating the template');
            // return response()->json(['error' => 'An error occurred while updating the template: ' . $e->getMessage()], 500);
            return redirect()->route('stuProposalSubmission.details', ['submissionPostId' => $submissionPostId])->with('errorUpdate', 'Some errors occur to update Proposal submission.');

        }

    }

    public function deleteProposalSubmission($proposalSubmissionId)
    {
        $proposalSubmission = Proposal_submission::find($proposalSubmissionId);
        // dd($thesisSubmission);

        if (!$proposalSubmission) {
            return redirect()->back()->with('error', 'Proposal submission not found.');
        }

        // Perform any additional checks (e.g., user authorization) before deletion
        $proposalSubmission->delete();

        return redirect()->back()->with('success', 'Proposal submission deleted successfully.');
    }

    public function removeFile(Request $request, $formSubmissionId)
    {
        // Validate the request
        $request->validate([
            'path' => 'required|string', // Assuming 'path' is the key sent in the AJAX request
        ]);
        // Find the form submission record
        $formSubmission = Proposal_submission::findOrFail($formSubmissionId);

        // Decode the form_files JSON
        $formFiles = json_decode($formSubmission->proposal_file, true);
        // dd($formFiles);

        // Find the index of the file to be removed
        $indexToRemove = array_search($request->input('path'), array_column($formFiles, 'path'));

        if ($indexToRemove !== false) {
            // Remove the file from the array
            array_splice($formFiles, $indexToRemove, 1);

            // Encode the updated form_files array back to JSON
            $formSubmission->proposal_file = json_encode($formFiles);

            // Save the updated form submission record
            $formSubmission->save();

            return response()->json(['message' => 'File removed successfully']);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }
}
