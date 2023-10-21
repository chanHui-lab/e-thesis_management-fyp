<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\SubmissionPost;
use App\Models\Student;
use App\Models\Form_submission;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// reminder
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

//filevalidation
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
/**
     * Display a listing of the resource.
     */
    public function index()
    {

        // define nem of varibale then use back
        // $template = Template::latest()->paginate(3);
        $template = Template::getAdminFormTemplate();

        return view('admin.template_page.admintemplateupload',compact('template'));

    }

    public function test2()
    {

        // define nem of varibale then use back
        // $template = Template::latest()->paginate(3);

        // return view('admin.adminpage.admintemplateupload',compact('template'))-> with(request()->input('page'));
        return view('admin.template_page.adminallthesissubmission');

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('admin.adminpage.createform');
        return view('admin.template_page.newcreatetets');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file_name' => 'required',
            'description' => 'required',
            // 'submission_deadline' => 'required|date',
            'file_data' => 'required|mimes:pdf,doc,docx|max:2048', // Add validation rules for the file
            'status' => 'required| in:0,1',

        ]);

        // 3rd
        // $file = $request->file('file_data');
        // $fileName = time() . '_' . $file->getClientOriginalName();

        // // Store the file using the 'public' disk
        // $filePath = 'upload/templates/' . $fileName;
        // Storage::disk('public')->put($filePath, file_get_contents($file));

        // $extension = $file->getClientOriginalExtension();
        // $mime_type = $this->getMimeType($extension);


        $file = $request->file('file_data');
        $fileName = time() . '_' . $file->getClientOriginalName();
        // $filePath = $file->storeAs('upload/templates', $fileName);

        // Store the file using the 'public' disk
        $filePath = 'upload/templates/' . $fileName;
        Storage::disk('public')->put($filePath, file_get_contents($file));

        $extension = $file->getClientOriginalExtension();
        $mime_type = $this->getMimeType($extension);

        // try laterr
        $userId = Auth::user()->id;

        Template::create([
            'file_name' => $request->input('file_name'),
            'description' => $request->input('description'),
            'file_data' => $filePath, //its actually actual path
            'mime_type' => $mime_type, // set the MIME type
            'lecturer_id' => Auth::id(),
            'status' => $request->input('status'),
            'section' => "form"
        ]);

        //redirect user and send friendly message
        return redirect()->route('template.index')->with('success','Form Template created successfully');
        //appear in the if parttt success messages //'success' is the variable name
        }
        private function getMimeType($extension)
        {
            // Map file extensions to MIME types as needed
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    return 'image/jpeg';
                case 'png':
                    return 'image/png';
                case 'pdf':
                    return 'application/pdf';
                // Add more cases for other file types as needed
                default:
                    return 'application/octet-stream'; // Default MIME type
            }
        }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $template = Template::findOrFail($id); // Assuming Template is your model

        $filePath = $template->file_data; // Adjust the column name accordingly
        // $filePath = 'upload/templates/' . $template->file_data;

        // $pdfUrl = Storage::url($filePath);
        $pdfUrl = Storage::disk('public')->url($filePath);
        dd($pdfUrl);

        return view('admin.template_page.adminshowformtemplate', compact('template','pdfUrl'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        $data['getRecord'] = Template::getSingle($id);
        if(!empty($data['getRecord'])){
            return view('admin.template_page.admineditformtemplate',$data);
        }
        else{
            abort(404);
        }
        // return view('admin.adminpage.admineditformtemplate', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, Template $template, $id)
    // {
    //     //validate input
    //     $request->validate([
    //         'file_name' => 'required',
    //         'description' => 'required',
    //         'file_data' => 'required|mimes:pdf,doc,docx|max:2048', // Add validation rules for the file
    //         'status' => 'required| in:0,1',
    //     ]);

    //     try {
    //         $template->update($request->all());
    //     } catch (\Exception $e) {
    //         // Handle the error, log it, or return an error response
    //         return redirect()->back()->with('error', 'Failed to update the template.');
    //     }
    //     // //update a new product in database
    //     // $template->update($request->all());

    //     //redirect user and send friendly message
    //     return redirect()->route('template.index')->with('success','Template updated successfully');
    //     //appear in the if parttt success messages //'success' is the variable name

    // }
    public function update(Request $request, $id)
    {
        // Validate the incoming data
    $request->validate([
        'file_name' => 'required',
        'description' => 'required',
        'file_data' => 'required|mimes:pdf,doc,docx|max:2048', // Add validation rules for the file
        'status' => 'required|in:0,1',
    ]);

    // Retrieve the Template model by ID
    $template = Template::findOrFail($id);

    // Update the model with the validated data
    $template->file_name = trim($request->file_name);
    $template->description = trim($request->description);

    if ($request->hasFile('file_data')) {
        // Handle file upload here, and then update the file_data attribute with the new file path
        // For example, you can use the store() method to store the uploaded file and update the file_data attribute
        $file = $request->file('file_data');
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Store the file using the 'public' disk
        $filePath = 'upload/templates/' . $fileName;
        Storage::disk('public')->put($filePath, file_get_contents($file));

        $template->file_data = $filePath;
    }

    // Update the status attribute with the validated status
    $template->status = $request->status;
    // Save the updated model to the database
    $template->save();

        return redirect()->route('template.index')->with('success','Template updated successfully');

    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        $product = Template::find($id);

        if (!$product) {
            return redirect()->route('template.index')->with('error', 'Product not found');
        }

        $product->delete();

        return redirect()->route('template.index')->with('success', 'Product deleted successfully');
    }

    public function downloadFile ($id){

        $file = Product::findOrFail($id);
        $path = storage_path('app/' . $file->file_path);

        return response()->download($path, $file->file_name);
    }

    public function testthesis()
    {

    // define nem of varibale then use back
    // $template = Template::latest()->paginate(3);

    // return view('admin.adminpage.admintemplateupload',compact('template'))-> with(request()->input('page'));
    // return view('admin.thesispage.thesistemplateupload');
    return view('admin.calendar_page.edit_calendar');

    }

    //****************************************************************************************************************************** */
    public function indexPost()
    {

        // define nem of varibale then use back
        // $template = Template::latest()->paginate(3);
        $post = SubmissionPost::getAdminFormSP();

        // Calculate remaining time for each submission
        foreach ($post as $postform) {
            $postform->remainingTime = $this->calculateRemainingTime($postform->submission_deadline);
        }

        return view('admin.submission_post.allformpost',compact('post'));

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
        return view('admin.submission_post.create_formS');
    }

    public function storePost(Request $request)
    {
        // $data =
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'submission_deadline' => 'required|date',
            'files.*' => 'mimes:pdf,doc,docx|max:2048',
            'visibility_status' => 'required|in:0,1',
        ]);

        // $filePaths = [];
        // foreach ($request->file('files') as $file) {
        //     $path = $file->store('pdfs');
        //     $filePaths[] = $path;
        // }

        // Initialize an empty array to store file paths
        $filePaths = [];

        // Handle file uploads and store them
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = $file->getClientOriginalName();
                // Store the file in the public/upload/templates directory
                $file->storeAs('public/upload/templates', $filename);
                $filePaths[] = 'upload/templates/' . $filename; // Store the file path
            }
        }

        // Add the file paths to the data array
        // $data['files'] = $filePaths;

        SubmissionPost::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'submission_deadline'=> $request->input('submission_deadline'),
            'files' => json_encode($filePaths),
            'visibility_status' => $request->input('visibility_status'),
            'lecturer_id' => Auth::id(),
            'section' => "form",
        ]);

        // $post = SubmissionPost::create($data);

        //redirect user and send friendly message
        return redirect()->route('formpost.index')->with('success','Submission post created successfully');
        //appear in the if parttt success messages //'success' is the variable name
        }

    public function download(SubmissionPost $post, $filename)
    {
        // Decode the JSON data
        $filePaths = json_decode($post->files);

        // Ensure the requested file exists in the template's file paths
        if (in_array($filename, $filePaths)) {
            $path = storage_path('app/public/' . $filename);
            return response()->download($path);
        } else {
            return back()->with('error', 'File not found.');
        }
    }

    public function destroyPost($id)
    {
        $postform = SubmissionPost::find($id);

        if (!$postform) {
            return redirect()->route('formpost.index')->with('error', 'Product not found');
        }

        $postform->delete();

        return redirect()->route('formpost.index')->with('success', 'Product deleted successfully');
    }

     /**
     * Show the form for editing the specified resource.
     */
    public function editPost($id)
    {
        $data['getRecord'] = SubmissionPost::getSingle($id);

        if(!empty($data['getRecord'])){
            return view('admin.submission_post.edit_formS', $data);
        }
        else{
            return redirect()->route('formpost.index')->with('error', 'Submission post not found');
        }
    }

    // public function updatePost(Request $request, $id)
    // {
    //     // try {

    //     // Validate the incoming data
    //     $request->validate([
    //         'title' => 'required',
    //         'description' => 'required',
    //         'submission_deadline' => 'required|date',
    //         // 'files.*' => 'mimes:pdf,doc,docx|max:2048',
    //     ]);

    //     // Retrieve the Template model by ID
    //     $post = Template::findOrFail($id);

    //     // Check if the post exists
    //     if (!$post) {
    //         // Handle the case when the post doesn't exist
    //         return redirect()->route('formpost.index')->with('error', 'Submission post not found');
    //     }

    //     // Validate uploaded files
    //     $fileValidation = $this->validateFiles($request->file('files'));

    //     if ($fileValidation->fails()) {
    //         //handle file validation error
    //         return redirect()->back()->withErrors($fileValidation)->withInput();
    //     }

    //     $filePaths = [];
    //     // if ($request->hasFile('files')) {
    //     //     foreach ($request->file('files') as $file) {
    //     //         $filename = $file->getClientOriginalName();
    //     //         $file->storeAs('public/upload/templates', $filename);
    //     //         $filePaths[] = 'upload/templates/' . $filename;
    //     //     }
    //     // }

    //     if ($request->hasFile('files')) {
    //         foreach ($request->file('files') as $file) {
    //             $filename = $file->getClientOriginalName();
    //             $file->storeAs('public/upload/templates', $filename);
    //             $filePaths[] = 'upload/templates/' . $filename;
    //         }
    //     }

    //     // Update the database with the new file paths
    //     $existingFiles = json_decode($post->files, true) ?? [];
    //     $existingFiles = array_merge($existingFiles, $filePaths);
    //     // $post->files = json_encode($existingFiles);

    //     // Update the submission post with the new data
    //     // $post->update([
    //     //     'title' => $request->input('title'),
    //     //     'description' => $request->input('description'),
    //     //     'submission_deadline' => $request->input('submission_deadline'),
    //     //     // 'files' => json_encode($filePaths),
    //     //     'files' => json_encode($existingFiles),
    //     // ]);

    //     $post->title = $request->input('title');
    //     $post->description = $request->input('description');
    //     $post->submission_deadline = $request->input('submission_deadline');
    //     $post->files = json_encode($existingFiles);

    //     // Save the updated record
    //     $post->save();

    //     return redirect()->route('formpost.index')->with('success','Template updated successfully');

    // // } catch (\Exception $e) {
    // //         // Handle exceptions, such as ModelNotFoundException or file storage errors
    // //         return redirect()->route('formpost.index')->with('error', 'An error occurred while updating the template');
    // // }
    // }
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
        $validation = $this->validateFiles($request->file('files'), $id);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        $filePaths = [];

        // Handle file uploads
        if ($request->hasFile('files')) {
            $newFiles = $request->file('files');
            $existingFiles = json_decode($post->files, true) ?? [];

            foreach ($newFiles as $file) {

                $filename = $file->getClientOriginalName();

                // if (in_array($filename, $existingFiles)) {
                //     return redirect()->back()->with('error', 'A file with the same name already exists.');
                // }

                // if (Storage::exists('public/upload/templates/' . $filename)) {
                //     // Handle the case when a file with the same name already exists
                //     return redirect()->back()->with('errorit', 'A file with the same name already exists.');
                // }

                // Ensure that the uploaded filenames are unique
                $existingFilenames = array_map('basename', $existingFiles);
                $newFilenames = array_map('basename', $filePaths);
                $duplicateFilenames = array_intersect($existingFilenames, $newFilenames);

                if (!empty($duplicateFilenames)) {
                    return redirect()->back()->with('error', 'Duplicate filenames found: ' . implode(', ', $duplicateFilenames));
                }

                    $file->storeAs('public/upload/templates', $filename);
                    $filePaths[] = 'upload/templates/' . $filename;
                    // Store the file path and upload time in the database
                    // $filePaths[] = [
                    //     'path' => 'upload/templates/' . $filename,
                    //     'uploaded_at' => now(), // Get the current time
                    // ];
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
        return redirect()->route('formpost.index')->with('success','Template updated successfully');
    } catch (\Exception $e) {
                // Handle exceptions, such as ModelNotFoundException or file storage errors
                return redirect()->route('formpost.index')->with('error', 'An error occurred while updating the template');
        }
    }
    protected function validateFiles(array $files)
{
    $validationRules = [
        'files.*' => [
            'file',
            'mimes:pdf,doc,docx',
            // Rule::unique('submission_posts', 'files') //within the table, not the specofoc record
        ],
    ];

    $customMessages = [
        'files.*.file' => 'Invalid file format.',
        'files.*.mimes' => 'Allowed file types are pdf, doc, and docx.',
        'files.*.unique' => 'A file with the same name already exists.',
    ];

    return Validator::make(['files' => $files], $validationRules, $customMessages);
}

// 2nd that dost work, no message pop up
    // protected function validateFiles(array $files)
    // {
    //     return Validator::make($files, [
    //         'files.*' => [
    //             'required',
    //             'file',
    //             'mimes:pdf,doc,docx',
    //             // Rule::dimensions()->maxWidth(1024)->maxHeight(768), // Optional: Add image dimensions validation
    //             'max:2048', // Maximum file size in kilobytes
    //         ],
    //     ], [
    //         'files.*.required' => 'The file is required.',
    //         'files.*.file' => 'Invalid file format.',
    //         'files.*.mimes' => 'Allowed file types are pdf and docx.',
    //         // 'files.*.dimensions' => 'The image dimensions are too large.',
    //         'files.*.max' => 'File size cannot exceed 2MB.',
    //     ]);
    // }

    // protected function validateFiles(array $files)
    // {
    //     return Validator::make($files, [
    //         'files.*' => [
    //             'required',
    //             'file',
    //             Rule::dimensions()->maxWidth(1024)->maxHeight(768), // Optional: Add image dimensions validation
    //             'max:2048', // Maximum file size in kilobytes
    //             function ($attribute, $value, $fail) use ($files) {
    //                 $allowedExtensions = ['pdf', 'doc', 'docx'];
    //                 $allowedMimeTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

    //                 $extension = $value->getClientOriginalExtension();
    //                 $mimeType = $value->getClientMimeType();
    //                 $filename = $value->getClientOriginalName();

    //                 if (!in_array($extension, $allowedExtensions) || !in_array($mimeType, $allowedMimeTypes)) {
    //                     $fail("The {$attribute} file must be a PDF, DOC, or DOCX.");
    //                 }

    //                 // Calculate a unique hash for the file contents
    //                 $fileHash = md5_file($value->getPathname());

    //                 // Check if the hash already exists in the database or other storage
    //                 if (Storage::exists('file_hashes/' . $fileHash)) {
    //                     $fail("Duplicate file detected.");
    //                 } else {
    //                     // Store the hash for future reference
    //                     Storage::put('file_hashes/' . $fileHash, '');
    //                 }
    //             },
    //         ],
    //     ], [
    //         'files.*.required' => 'The file is required.',
    //         'files.*.file' => 'Invalid file format.',
    //         'files.*.dimensions' => 'The image dimensions are too large.',
    //         'files.*.max' => 'File size cannot exceed 2MB.',
    //     ]);
    // }


// protected function validateFiles(array $files)
// {
//     return Validator::make($files, [
//         'files.*' => [
//             'required',
//             'file',
//             'mimes:pdf,doc,docx',
//             Rule::dimensions()->maxWidth(1024)->maxHeight(768), // Optional: Add image dimensions validation
//             'max:2048', // Maximum file size in kilobytes
//         ],
//     ], [
//         'files.*.required' => 'The file is required.',
//         'files.*.file' => 'Invalid file format.',
//         'files.*.mimes' => 'Allowed file types are pdf and docx.',
//         'files.*.dimensions' => 'The image dimensions are too large.',
//         'files.*.max' => 'File size cannot exceed 2MB.',
//     ]);
// }

public function removeFile(Request $request, $id)
{
    // $response = ['message' => 'File removed successfully'];
    // dd($response);

    $file = json_decode($request->getContent(), true)['file'];
    // dd( $file);

    try {
        // Delete the file from storage
        Storage::delete('public/upload/templates/' . $file);

        // Remove the file from the list of existing files
        $getRecord = SubmissionPost::find($id);
        $existingFiles = json_decode($getRecord->files, true) ?? [];
        $existingFiles = array_diff($existingFiles, [$file]);
        $getRecord->files = json_encode(array_values($existingFiles));
        $getRecord->save();

        return response()->json(['message' => 'File removed successfully']);
        // return redirect()->back()->with('successdelete','File removed successfully');
        // return redirect()->route('formpost.edit', ['id' => $id])->with('successdelete', 'Files removed successfully.');

        // return redirect()->back()->with('success', 'File removed successfully');
        // cannt put like this with json!!
        // return redirect()->back();
        // return redirect()->back()->with('success', 'File removed successfully');

    } catch (\Exception $e) {
        // Handle any exceptions that may occur during file deletion or database update
        return response()->json(['error' => 'An error occurred while updating the template'], 500);
    }
    // return redirect()->back();
}

// // view the submision post from certain students only
//     public function getSubmissionPostsForLecturer()
//     {
//         // Get the currently logged-in lecturer's user ID
//         $lecturerId = auth()->user()->id;

//         // Retrieve students supervised by the lecturer
//         $students = Student::where('supervisor_id', $lecturerId)->get();

//         // Get the submission posts associated with the students
//         $submissionPosts = SubmissionPost::whereIn('stu_id', $students->pluck('stu_id'))->get();

//         return view('admin.submission_posts.viewAll', compact('submissionPosts'));
//     }

// }

// version 1 that is weird?
    // public function getSubmissionFormForLecturer($submissionPostId){
    //    $submissionPost = SubmissionPost::find($submissionPostId); // Fetch the desired submission post

    //     $lecturer = Auth::user(); // Assuming you're using Laravel's built-in authentication

    //     // Fetch the form submissions for the submission post and supervised students
    //     $formsubmissions = $submissionPost->forms()->whereHas('student', function ($query) use ($lecturer) {
    //         $query->where('supervisor_id', $lecturer->id);
    //     })->get();

    //     return view('admin.submission_post.viewAll', compact('submissionPost', 'formsubmissions'));

    // }

    // version 2 to fetch FORM SUBMISSION data from forms table for lectuere
    // public function getSubmissionFormForLecturer()
    // {
    //     $lecturer = Auth::user(); // Assuming you're using Laravel's built-in authentication

    //     // Retrieve the students supervised by the lecturer
    //     $supervisedStudents = $lecturer->supervisedStudents;

    //     // Retrieve the forms submitted by these supervised students
    //     $forms = Form::whereIn('student_id', $supervisedStudents->pluck('id'))->get();

    //     return view('lecturer.view', compact('forms'));
    // }

    // version 3
    public function getForsdmSubmissionForLecturer($submissionPostId)
    {
        // Retrieve the specific submission post
        $submissionPost = SubmissionPost::find($submissionPostId);
        $lecturer = auth()->user();
        // dd($lecturer);
        // $lecturer = Auth::user();

        // Check if the logged-in user is authorized to view submissions for this post
        // if (auth()->user()->id === $submissionPost->lecturer_id) {
            // Retrieve the lecturer's supervised students
            // $supervisedStudents = auth()->user()->student();

            // // dd($supervisedStudents);
            // // Retrieve the forms submitted for this submission post by supervised students
            // $forms = Form_submission::with('supervisor')->where('submission_post_id', $submissionPostId)
            //     ->whereIn('student_id', $supervisedStudents->pluck('stu_id'))
            //     ->get();


            // if ($submissionPost->lecturer->id === $lecturer->id) {
            //     // $formSubmissions = Form_submission::where('submission_post_id', $submissionPost->id)
            //     //     ->get();

            //     $formSubmissions = $submissionPost->formSubmissions()
            //         ->whereIn('stu_id', $lecturer->supervisedStudents->pluck('id'))
            //         ->get();
            // } else {
            //     $formSubmissions = collect(); // Return an empty collection if the lecturer is not authorized
            // }

            // if ($submissionPost->lecturer->id === $lecturer->id) {
            //     $formSubmissions = $lecturer->formSubmissions()->get();
            // }
            if ($submissionPost->lecturer_id ===  auth()->user()->id ) {
                $formSubmissions = $lecturer ->formSubmissions
                    ->where('submission_post_id', $submissionPost->id);
            } else {
                $formSubmissions = collect(); // Return an empty collection if the lecturer is not authorized
            }

            return view('admin.submission_post.viewAll', compact('formSubmissions','submissionPost'));
        // } else {
        //     abort(403, 'Unauthorized');
        // }
    }

    public function getFormSubmissionForLecturer($submissionPostId){
        $lecturer = auth()->user(); // Get the currently logged-in lecturer
        // $lecturer->load('formSubmissions'); // Eager load the formSubmissions relationship
        $submissionPost = SubmissionPost::find($submissionPostId);

        if ($submissionPost->lecturer->id === $lecturer->id) {
            $formSubmissions = $lecturer->formSubmissions()
                ->where('submission_post_id', $submissionPost->id)
                ->get();
        }
        // dd($formSubmissions);
        return view('admin.submission_post.viewAll', compact('formSubmissions','submissionPost'));
    }

    public function showFormSubmissions($submissionPostId,$formSubmissionId)
    {
        $submissionPost = SubmissionPost::find($submissionPostId);
        // // Retrieve the form submissions related to the selected submission post
        // $formSubmissions = $submissionPost->formSubmissions;
        DB::enableQueryLog();

        $formSubmission = Form_submission::find($formSubmissionId);

        \Log::info(DB::getQueryLog());

        // dd($formSubmission);
        // return view('admin.submission_post.viewOne', compact('formSubmission'));

        return view('admin.submission_post.viewOne', compact('formSubmission', 'submissionPost'));
    }

    }



