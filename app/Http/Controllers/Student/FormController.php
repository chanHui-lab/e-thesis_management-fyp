<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use App\Models\Template;
use App\Models\SubmissionPost;
use App\Models\Student;
use App\Models\Form_submission;
use App\Models\Comment;

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


    // used this before adding comment
    // public function showStuFormSubmissionDetails($id)
    // {
    //     // Fetch the submission post details
    //     // $submissionPost = SubmissionPost::getSingle($id);
    //     $submissionPost = SubmissionPost::where('id', $id)->first();

    //     // $submissionPost = DB::table('submission_posts')->find(40);

    //     // dd($submissionPost);

    //     if (!$submissionPost) {
    //         abort(404); // or handle the error in another way, e.g., redirect
    //     }

    //     // Check if the current user has permission to view this submission
    //     // You may need to implement your own logic for permission checking

    //     // Fetch form submissions related to the submission post for the current student
    //     $formSubmission = $submissionPost->formSubmissions()
    //         ->where('student_id', Auth::id())
    //         ->first();
    //     // dd($formSubmission);

    //     return view('student.stuform.formSubmissionStu', ['submissionPost' => $submissionPost, 'formSubmission' => $formSubmission]);
    // }

    //after adding comment
    public function showStuFormSubmissionDetails($submissionPostId)
    {
        // Fetch the submission post details
        $submissionPost = SubmissionPost::where('id', $submissionPostId)->first();

        if (!$submissionPost) {
            abort(404);
        }

        // Fetch form submissions related to the submission post for the current student
        $formSubmission = $submissionPost->formSubmissions()
            ->where('student_id', Auth::id())
            ->first();

        // Call the method to get form submission details along with comments
        return $this->showFormSubmission($formSubmission->id,$submissionPost);
    }

    public function showFormSubmission($formSubmissionId,$submissionPost)
    {
        // $formSubmission = Form_submission::with('comments.student.user')->findOrFail($formSubmissionId);
        $formSubmission = Form_submission::findOrFail($formSubmissionId);
        // $comments = $formSubmission->comments;
        $comments = Comment::where('commentable_id', $formSubmission->id)
        ->where(function ($query) use ($formSubmission) {
            // Include comments made by the student
            $query->where('student_id', Auth::id())
                // Include comments made by the lecturer who supervises the student
                ->orWhere('lecturer_id', $formSubmission->supervisor_id);
        })
        ->get();
        // dd($comments);
        $allcomments = $formSubmission->comments;
        // dd($allcomments);


        return view('student.stuform.formSubmissionStu', compact('submissionPost','formSubmission', 'comments'));
    }


    public function createFormSubmission($submissionPostId)
    {
        return view('student.stuform.formSubCreate', compact('submissionPostId'));
    }

    public function storeFormSubmission(Request $request)
    {
        $request->validate([
            'form_title' => 'required',
            'description' => 'required',
            'files.*' => 'mimes:pdf,doc,docx',
        ]);
        $submissionPostId = $request->input('submission_post_id');

        $filePaths = [];

        // Handle file uploads and store them
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = $file->getClientOriginalName();
                $userId = auth()->user()->id;
                $currentTime = now();
                $formattedTimestamp = $currentTime->format('Ymd_His');
                // Store the file in the public/upload/templates directory
                $userFilePath = "upload/formSubmission/stu_{$userId}/{$formattedTimestamp}";
                $file->storeAs("public/$userFilePath", $filename);
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
            // dd($supervisorId);

        } else {
            // dd($student);
        }

        Form_submission::create([
            'form_title' => $request->input('form_title'),
            'description' => $request->input('description'),
            'form_files' => json_encode($filePaths), //its actually actual path
            'student_id'=> Auth::id(),
            'supervisor_id' => $supervisorId,
            'submission_post_id' => $submissionPostId,
            'sem_id' => 1,
        ]);
        return redirect()->route('stutemplate.index')->with('success', 'Form submitted successfully.'); // Redirect back with a success message

        // return redirect()->route('stuFormSubmission.index', ['id' => $submissionPostId])->with('success', 'Form submitted successfully.'); // Redirect back with a success message

    }

    public function deleteFormSubmission($formSubmissionId)
    {
        $formSubmission = Form_submission::find($formSubmissionId);

        if (!$formSubmission) {
            return redirect()->back()->with('error', 'Form submission not found.');
        }

        // Perform any additional checks (e.g., user authorization) before deletion
        $formSubmission->delete();

        return redirect()->back()->with('success', 'Form submission deleted successfully.');
    }

    public function editFormSubmission($formSubmissionId,$submissionPostId)
    {
        // Fetch the form submission by ID and perform necessary logic
        // $formSubmission = Form_submission::find($formSubmissionId);
        $data['getRecord'] = Form_submission::getSingle($formSubmissionId);
        // $getRecord = Form_submission::getSingle($formSubmissionId);

        // dd($submissionPostId);

        // Return the view for editing with the form submission data
        return view('student.stuform.formSubEdit', $data,compact('submissionPostId'));
        // return view('student.stuform.formSubEdit', ['formSubmission' => $formSubmission]);
    }

    public function updateFormSubmission(Request $request, $formSubmissionId)
    {

        // Fetch the form submission by ID
        $formSubmission = Form_submission::find($formSubmissionId);

        try{
        // dd($request->all());
        $request->validate([
            'form_title' => 'required',
            'files.*' => 'nullable|mimes:pdf,doc,docx',
        ]);

        $filePaths = [];
        if ($request->hasFile('files')) {
            $newFiles = $request->file('files');
            $existingFiles = json_decode($formSubmission->form_files, true) ?? [];
            // dd($existingFiles);

            foreach ($newFiles as $file) {
                $userId = auth()->user()->id;
                $currentTime = now();
                $formattedTimestamp = $currentTime->format('Ymd_His');
                // Store the file in the public/upload/templates directory
                $userFilePath = "upload/formSubmission/stu_{$userId}/{$formattedTimestamp}";
                $filename = $file->getClientOriginalName();

                $existingFilenames = array_column($existingFiles, 'path');
                if (in_array("$userFilePath/$filename", $existingFilenames)) {
                    return redirect()->back()->with('error', 'Duplicate filename found: ' . "$userFilePath/$filename");
                }

                $file->storeAs("public/$userFilePath", $filename);


                // Ensure that the uploaded filenames are unique
                // $existingFilenames = array_map('basename', $existingFiles);
                // $newFilenames = array_map('basename', $filePaths);
                // $duplicateFilenames = array_intersect($existingFilenames, $newFilenames);

                // if (!empty($duplicateFilenames)) {
                //     return redirect()->back()->with('error', 'Duplicate filenames found: ' . implode(', ', $duplicateFilenames));
                // }


                $existingFiles[] = [
                    'path' => "$userFilePath/$filename",
                    'uploaded_at' => $currentTime,
                ];

                // $existingFiles = json_decode($formSubmission->form_files, true) ?? [];
                // $existingFiles = array_merge($existingFiles, $filePaths);

            }
            $formSubmission->form_files = json_encode($existingFiles);
        }
        // Update the form submission with new data from the request
        // $formSubmission->update([
        //     'form_title' => $request->input('form_title'),
        //     'description' =>$request->input('description'),
        //     // Add other fields as needed
        // ]);
        // Update other fields (e.g., title, description) as needed
        $formSubmission->form_title = $request->input('form_title');
        $formSubmission->description = $request->input('description');

        // Save the updated record
        $formSubmission->save();

        // Redirect the user to the view page or any other appropriate route
        // return redirect()->route('stuFormSubmission.details')->with('success', 'Form submission updated successfully.');
        return redirect()->route('stutemplate.index')->with('success', 'Form submission updated successfully.');

        }
        catch(\Exception $e){
            // return redirect()->route('stuFormSubmission.details',['formSubmissionId' => $formSubmission->id])->with('error', 'An error occurred while updating the template');
            return response()->json(['error' => 'An error occurred while updating the template: ' . $e->getMessage()], 500);
        }

    }
    // public function removeFileForm(Request $request, $formId, $fileIndex)
    // {
    //     try {
    //         \Log::info("formId hereee: $formId, fileIndex: $fileIndex");
    //         $form = Form_submission::findOrFail($formId);
    //         // $form = Form_submission::findOrFail($request->formSubmissionId);
    //         \Log::info("formm hereee: $form");
    //         $formFiles = json_decode($form->form_files, true);
    //         \Log::info("formFiles hereee: $formFiles");
    //         // dd($formFiles);
    //         // Validate that $fileIndex is a valid index in $formFiles array
    //         if (isset($formFiles[$fileIndex])) {
    //             $filePath = $formFiles[$fileIndex]['path'];
    //             \Log::info("$filePath");

    //             // Remove the file from storage
    //             Storage::delete($filePath);

    //             // Remove the file entry from the form_files array
    //             unset($formFiles[$fileIndex]);

    //             // Update the database record
    //             $form->update(['form_files' => json_encode($formFiles)]);
    //             // dd($formFiles);

    //             return response()->json(['success' => true]);
    //         }

    //             return response()->json(['error' => 'Invalid file index']);
    //         } catch (\Exception $e) {
    //             \Log::error('Exception while removing file:', ['error' => $e->getMessage(), 'trace' => $e->getTrace()]);

    //             return response()->json(['error' => 'Error removing file']);
    //         }
    //     }
    // }

    public function removeFile(Request $request, $formSubmissionId)
    {
        // Validate the request
        $request->validate([
            'path' => 'required|string', // Assuming 'path' is the key sent in the AJAX request
        ]);
        // Find the form submission record
        $formSubmission = Form_submission::findOrFail($formSubmissionId);

        // Decode the form_files JSON
        $formFiles = json_decode($formSubmission->form_files, true);

        // Find the index of the file to be removed
        $indexToRemove = array_search($request->input('path'), array_column($formFiles, 'path'));

        if ($indexToRemove !== false) {
            // Remove the file from the array
            array_splice($formFiles, $indexToRemove, 1);

            // Encode the updated form_files array back to JSON
            $formSubmission->form_files = json_encode($formFiles);

            // Save the updated form submission record
            $formSubmission->save();

            return response()->json(['message' => 'File removed successfully']);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }
    }
