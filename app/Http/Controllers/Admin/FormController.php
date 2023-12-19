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
     * Store a newly created template resource in storage.
     */
    public function store(Request $request){
        $request->validate([
            'file_name' => 'required',
            'description' => 'required',
            // 'submission_deadline' => 'required|date',
            'file_data.*' => 'mimes:pdf,doc,docx', //add the columns
            // 'file_data' => 'required|mimes:pdf,doc,docx|max:2048', // Add validation rules for the file
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

        // // FIRST TECHNIQUE
        // $file = $request->file('file_data');
        // $fileName = time() . '_' . $file->getClientOriginalName();

        // // Store the file using the 'public' disk
        // $filePath = 'upload/templates/' . $fileName;
        // Storage::disk('public')->put($filePath, file_get_contents($file));

        // $extension = $file->getClientOriginalExtension();
        // $mime_type = $this->getMimeType($extension);
        // // END FIRST TECHNIQUE
        // Initialize an empty array to store file paths
        $filePaths = [];

        // Handle file uploads and store them
        if ($request->hasFile('file_data')) {
            foreach ($request->file('file_data') as $file) {
                $filename = $file->getClientOriginalName();
                // Store the file in the public/upload/templates directory
                // $file->storeAs('public/upload/templates', $filename);
                // $filePaths[] = 'upload/templates/' . $filename; // Store the file path
                $extension = $file->getClientOriginalExtension();
                $mime_type = $this->getMimeType($extension);

                // Get the current logged-in user's ID
                $userId = auth()->user()->id;
                // Get the current time
                $currentTime = now();

                // Format the timestamp (optional, adjust as needed)
                $formattedTimestamp = $currentTime->format('Ymd_His');

                // Append the user's ID to the file path
                // $userFilePath = 'upload/templates/user_' . $userId;

                // Append the user's ID and timestamp to the file path
                $userFilePath = "upload/templates/user_{$userId}/{$formattedTimestamp}";

                // Store the file in the public/upload/templates/user_{user_id} directory
                $file->storeAs("public/$userFilePath", $filename);

                $filePaths[] = [
                    'path' => "$userFilePath/$filename",
                    'uploaded_at' => $currentTime,
                ];
            }
        }

        Template::create([
            'file_name' => $request->input('file_name'),
            'description' => $request->input('description'),
            'file_data' => json_encode($filePaths), //its actually actual path
            'mime_type' => 'pdf', // set the MIME type
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
            'file_data' => 'mimes:pdf,doc,docx|max:2048', // Add validation rules for the file
            'status' => 'required|in:0,1',
        ]);

    // Retrieve the Template model by ID
    $template = Template::findOrFail($id);

    // Update the model with the validated data
    $template->file_name = trim($request->file_name);
    $template->description = trim($request->description);

    // if ($request->hasFile('file_data')) {
    //     // Handle file upload here, and then update the file_data attribute with the new file path
    //     // For example, you can use the store() method to store the uploaded file and update the file_data attribute
    //     $file = $request->file('file_data');
    //     $fileName = time() . '_' . $file->getClientOriginalName();

    //     // Store the file using the 'public' disk
    //     $filePath = 'upload/templates/' . $fileName;
    //     Storage::disk('public')->put($filePath, file_get_contents($file));

    //     $template->file_data = $filePath;
    // }
    $filePaths = [];
    // Handle file uploads and store them
     if ($request->hasFile('file_data')) {
        foreach ($request->file('file_data') as $file) {
            $filename = $file->getClientOriginalName();
            // Store the file in the public/upload/templates directory
            // $file->storeAs('public/upload/templates', $filename);
            // $filePaths[] = 'upload/templates/' . $filename; // Store the file path
            $extension = $file->getClientOriginalExtension();
            $mime_type = $this->getMimeType($extension);

            // Get the current logged-in user's ID
            $userId = auth()->user()->id;
            // Get the current time
            $currentTime = now();

            // Format the timestamp (optional, adjust as needed)
            $formattedTimestamp = $currentTime->format('Ymd_His');

            // Append the user's ID to the file path
            // $userFilePath = 'upload/templates/user_' . $userId;

            // Append the user's ID and timestamp to the file path
            $userFilePath = "upload/templates/user_{$userId}/{$formattedTimestamp}";

            // Store the file in the public/upload/templates/user_{user_id} directory
            $file->storeAs("public/$userFilePath", $filename);

            $filePaths[] = [
                'path' => "$userFilePath/$filename",
                'uploaded_at' => $currentTime,
            ];
        }
    }

    // Update the status attribute with the validated status
    $template->status = $request->status;
    $template->file_data = json_encode($filePaths); //its actually actual path
    // dd($template);

    // Save the updated model to the database
    $template->save();

    return redirect()->route('template.index')->with('success','Template updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        $template = Template::find($id);

        if (!$template) {
            return redirect()->route('template.index')->with('error', 'Template not found');
        }

        $template->delete();

        return redirect()->route('template.index')->with('success', 'Template deleted successfully');
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
                $mime_type = $this->getMimeType($extension);

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
        // $validation = $this->validateFiles($request->file('files'), $id);
        // if ($validation->fails()) {
        //     return redirect()->back()->withErrors($validation)->withInput();
        // }

        $filePaths = [];

        // Handle file uploads
        // if ($request->hasFile('files')) {
        //     $newFiles = $request->file('files');
        //     $existingFiles = json_decode($post->files, true) ?? [];
        //     dd($newFiles);

        //     foreach ($newFiles as $file) {
        //         $filename = $file->getClientOriginalName();

        //         // Ensure that the uploaded filenames are unique
        //         $existingFilenames = array_map('basename', $existingFiles);
        //         $newFilenames = array_map('basename', $filePaths);
        //         $duplicateFilenames = array_intersect($existingFilenames, $newFilenames);

        //         if (!empty($duplicateFilenames)) {
        //             return redirect()->back()->with('error', 'Duplicate filenames found: ' . implode(', ', $duplicateFilenames));
        //         }

        //             $file->storeAs('public/upload/templates', $filename);
        //             // $filePaths[] = 'upload/templates/' . $filename;
        //             dd($filePaths[]);
        //             // Store the file path and upload time in the database
        //             $filePaths[] = [
        //                 'path' => 'upload/templates/' . $filename,
        //                 'uploaded_at' => now(), // Get the current time
        //             ];
        //         }
        //     $existingFiles = json_decode($post->files, true) ?? [];
        //     $existingFiles = array_merge($existingFiles, $filePaths);

        //     $post->files = json_encode($existingFiles);
        // }

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

    // before de, DOENST WORK HAS INTERNAL ERRRO
    // public function removeFile(Request $request, $id)
    // {
    //     $file = json_decode($request->getContent(), true)['file'];
    //     // dd( $file);

    //     try {
    //         // Delete the file from storage
    //         Storage::delete('public/upload/templates/' . $file);

    //         // Remove the file from the list of existing files
    //         $getRecord = SubmissionPost::find($id);
    //         $existingFiles = json_decode($getRecord->files, true) ?? [];
    //         $existingFiles = array_diff($existingFiles, [$file]);
    //         $getRecord->files = json_encode(array_values($existingFiles));
    //         $getRecord->save();

    //         return response()->json(['message' => 'File removed successfully']);
    //         // return redirect()->back()->with('successdelete','File removed successfully');
    //         // return redirect()->route('formpost.edit', ['id' => $id])->with('successdelete', 'Files removed successfully.');

    //         // return redirect()->back()->with('success', 'File removed successfully');
    //         // cannt put like this with json!!
    //         // return redirect()->back();
    //         // return redirect()->back()->with('success', 'File removed successfully');

    //     } catch (\Exception $e) {
    //         // Handle any exceptions that may occur during file deletion or database update
    //         return response()->json(['error' => 'An error occurred while updating the template'], 500);
    //     }
    //     // return redirect()->back();
    // }

    // public function removeFile(Request $request)
    // {
    //     $filePath = $request->input('file_path');
    //     $file = json_decode($request->getContent(), true)['file'];
    //     \Log::info('Attempting to remove file: ' . $file);
    //     \Log::info('Attempting to remove file: ' . $filePath);
    //     // Validate the file path or perform additional security checks

    //     // Example: Use Storage facade to delete the file
    //     try {
    //         // Delete the file from storage
    //         // $encodedFilePath = urlencode($filePath);
    //         $decodedFilePath = urldecode($filePath);

    //         \Log::info('Attempting to remove file: ' . $decodedFilePath);
    //         // Storage::delete($decodedFilePath);
    //         Storage::delete($filePath);

    //         // Update your database or storage system accordingly

    //         return response()->json(['message' => 'File removed successfully']);
    //     } catch (\Exception $e) {
    //         \Log::error('Error removing file: ' . $e->getMessage());
    //         // Handle the exception, e.g., log the error
    //         return response()->json(['error' => 'Failed to remove the file'], 500);
    //     }
    // }

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
    //display the forms for all students, the kosong one cant be displayed
    public function TestgetFormSubmissionForLecturer($submissionPostId){
        $lecturer = auth()->user(); // Get the currently logged-in lecturer
        // $submissionPost = SubmissionPost::find($submissionPostId);
        $submissionPost = SubmissionPost::with('lecturer')->find($submissionPostId);

        if ($submissionPost->lecturer->id === $lecturer->id) {
            $formSubmissions = $lecturer->formSubmissions()
                ->where('submission_post_id', $submissionPost->id)
                ->get();
        }
        // dd($formSubmissions);
        return view('admin.submission_post.viewAll', compact('formSubmissions','submissionPost'));
    }

    public function getFormSubmissionForLecturer($submissionPostId){
        $lecturer = auth()->user(); // Get the currently logged-in lecturer
        // $submissionPost = SubmissionPost::find($submissionPostId);
        $submissionPost = SubmissionPost::with('lecturer')->find($submissionPostId);

        // if ($submissionPost->lecturer->id === $lecturer->id) {

        // added accesible for admin role
        if (($submissionPost && $submissionPost->lecturer && $submissionPost->lecturer->id === $lecturer->id)) {

            // Fetch form submissions for the given submission post
            // $formSubmissions = Form_submission::where('submission_post_id', $submissionPost->id)
            // ->where('supervisor_id', $lecturer->id)
            // ->get();

            // NEED TO ADD ONE PART, if lecture role is admin, access all student instead of joinging.
            $formSubmissions = $lecturer->formSubmissions()
            ->where('submission_post_id', $submissionPost->id)
            ->get();

            // Fetch students supervised by the lecturer along with their submission statuses
            $students = DB::table('students')
            ->select('students.stu_id', 'users.name AS student_name', 'students.matric_number')
            ->leftJoin('users', 'students.stu_id', '=', 'users.id')
            ->leftJoin('form_submissions', function ($join) use ($lecturer, $submissionPost) {
                    $join->on('students.stu_id', '=', 'form_submissions.student_id')
                        ->where('form_submissions.supervisor_id', $lecturer->id)
                        ->where('form_submissions.submission_post_id', $submissionPost->id);
                })
                ->get();
        // dd($formSubmissions);
        return view('admin.submission_post.viewAll', compact('formSubmissions','students','submissionPost'));
        }
    }

    // public function testshowFormSubmissions($formSubmissionId,$studentId)

    // {
    //     // $submissionPost = SubmissionPost::findOrFail($submissionPostId);
    //     $formSubmission = Form_submission::findOrFail($formSubmissionId);
    //     $student = Student::findOrFail($studentId);

    //     return view('admin.submission_post.viewOne', compact('formSubmission', 'submissionPost','student'));
    // }

    public function showFormSubmissions($formSubmissionId)

    {
        // $submissionPost = SubmissionPost::findOrFail($submissionPostId);
        $formSubmission = Form_submission::findOrFail($formSubmissionId);
        // $student = Student::findOrFail($studentId);
        $submissionPostId = $formSubmission->submissionPost->id;

        return view('admin.submission_post.viewOne', compact( 'formSubmission','submissionPostId'));
    }

    // test for vue part
    public function indexVueeee()
    {
        $theses = Form_submission::select('id', 'form_title', 'description') // Include only the needed columns
        ->get();
        // return response()->json($data);
        $data = $theses->toArray();

        // return view('tesetvue', ['data' => $data]);
        return view('tesetvue', compact('data'));

    }
    public function indexVue()
    {
        // $dashboardData = Form_submission::select('id', 'form_title', 'description') // Include only the needed columns
        // ->get();

        // return view('tesetvue', ['dashboardData' => $dashboardData]);
        // return response()->json($dashboardData);

        // $data = Form_submission::select('id', 'form_title', 'description') // Include only the needed columns
        // ->get();
        // return view('tesetvue', compact('data'));
        // return response()->json($data);

        return view('admin.thesisrepo_page.thesisrepo');

    }

    public function fetchdata()
    {
        $data = Form_submission::select('id', 'form_title', 'description','form_files','student_id') // Include only the needed columns
        ->get();
        return response()->json($data);
    }

    // public function downloadVue($filePath)
    // {
    //     // Check if the file exists
    // if (Storage::disk('public')->exists($filePath)) {
    //     // Get the full path to the file
    //     // $filePath = Storage::disk('public')->path($filePath);
    //     $filePathh = storage_path('app/' . $filePath);

    //     // Extract the filename from the path
    //     $fileName = pathinfo($filePathh, PATHINFO_BASENAME);

    //     // Set the headers for the response
    //     $headers = [
    //         'Content-Type' => 'application/octet-stream',
    //         'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    //     ];

    //     // Use Laravel's response()->download() method
    //     return response()->download($filePathh, $fileName, $headers);
    //     } else {
    //         // Handle the case when the file does not exist
    //         abort(404, 'File not found');
    //     }
    // }

    public function downloadVue($filePath) {
        // return Storage::disk('public')->download($filePath);
        // Get the full path of the file
        // dd($filePath);
        $path = storage_path('app/public/' . $filePath);

        // Get the file name
        $fileName = basename($filePath);

        // Download the file
        return response()->download($path, $fileName);

    }

    }
