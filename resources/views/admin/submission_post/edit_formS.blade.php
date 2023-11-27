@extends('admin.template_page.adminpure')

@section('master_content')

<main>
<h1 style = "padding-top: 20px; padding-bottom:20px">Edit Form Template</h1>

<a style = "margin-bottom: 20px;" class="btn btn-primary" href="{{ route('formpost.index') }}"> Back</a>
 {{--handle error  --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There are some problem with input <br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There are some problem with input <br><br>
        <ul>
            @foreach ($errors->all() as $errorit)
                <li>{{ $errorit }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div id="success-message" class="alert alert-success" style="display: none; color: green;"></div>
{{-- @if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif --}}

{{-- <div class = "containerr"> --}}
<!-- Create Post Form -->

<div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">Edit</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->

      <form method="POST" action="{{ route('formpost.update', ['id' => $getRecord->id]) }}" enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="card-body">
        <div class="form-group">
            <label for="title"  class = "labelling">Template Name:</label>
            <input type="text" id="title" name="title" class = "form-control" placeholder= "Title" value="{{ $getRecord -> title }}"><br>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" class="form-control" style="height: 150px" name="description" rows="4" cols="50" placeholder="description">{{ $getRecord->description }}</textarea><br>
          </div>

          <div class="form-group">
            <label for="submission_deadline">Submission Deadline:</label>
            <input type="datetime-local" id="submission_deadline" name="submission_deadline" class="form-control datetimepicker" placeholder="Submission Deadline" value="{{ $getRecord -> submission_deadline }}"><br>
          </div>

                {{-- <div class="wrapper"> --}}

                  <p>Current File:</p>

                  @php
                      $files = json_decode($getRecord->files, true);
                  @endphp
                @if (is_array($files) && count($files) > 0)
                <table class="table table-bordered" style = "width: 900px;">
                    <thead>
                        <tr>
                            <th class="wider-column">File Name</th>
                            {{-- <th>Uploaded Time</th> --}}
                            <th style = "width: 100px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($files as $filee)
                            <tr>
                                <td>
                                    {{-- <a href="{{ asset('storage/' . $file) }}" target="_blank" download>
                                        {{ basename($file) }}
                                    </a> --}}
                                    {{-- @if (Str::endsWith($file, '.pdf'))
                                        <a href="{{ asset('storage/' . $file) }}" target="_blank" download class="downloadfile-link">
                                            <i class="fa fa-file-pdf file-icon" style = "color: rgb(255, 86, 86)"></i> {{ basename($file) }}
                                        </a>
                                    @elseif (Str::endsWith($file, '.doc') || Str::endsWith($file, '.docx'))
                                        <a href="{{ asset('storage/' . $file) }}" target="_blank" download class="downloadfile-link">
                                            <i class="fa fa-file-word file-icon" style = "color: rgb(77, 144, 250)"></i> {{ basename($file) }}
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/' . $file) }}" target="_blank" download class="downloadfile-link">
                                            <i class="fa fa-file file-icon" style = "color: rgb(77, 144, 250)"></i> {{ basename($file) }}
                                        </a>
                                    @endif --}}

                                    <a href="{{ asset('storage/' . $filee['path']) }}" target="_blank" download class="downloadfile-link">
                                        @if (Str::endsWith($filee['path'], '.pdf'))
                                        <i class="fa fa-file-pdf file-icon" style = "color:  rgb(255, 86, 86)"></i>

                                        @elseif (Str::endsWith($filee['path'], '.doc') || Str::endsWith($filee['path'], '.docx'))
                                        <i class="fa fa-file-word file-icon" style = "color: rgb(77, 144, 250)"></i>

                                        @else
                                        <i class="fa fa-solid fa-file file-icon" style = "color: rgb(77, 144, 250)"></i>
                                        @endif
                                        {{ substr($filee['path'], strpos($filee['path'], '_') + 1) }}

                                    </a >

                                </td>
                                {{-- <td> --}}
                                    {{-- {{ \Carbon\Carbon::parse(\Storage::lastModified($file))->toDateTimeString() }} --}}
                                    {{-- {{ $file['uploaded_at'] }} --}}
                                    {{-- {{ $file['uploaded_at']->format('Y-m-d H:i:s') }} --}}
                                {{-- </td> --}}
                                <td>
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-modal-id="confirmationModal{{ $filee['path']}}" data-target="#confirmationModal{{ Str::slug(basename($filee['path'] )) }}">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- @else
                <p>No files uploaded.</p>
                @endif --}}

                {{-- @if (is_array($files) && count($files) > 0)
                        <ul class = "file-list">
                            @foreach ($files as $file)
                                <li>
                                    <div class="file-info">

                                        <a href="{{ asset('storage/' . $file) }}" target="_blank" download>
                                            {{ basename($file) }}
                                        </a>
                                    </div>
                                    <div class="remove-button">

                                    <!-- Add a "Remove" button to trigger the confirmation modal -->
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-modal-id="confirmationModal{{ $file }}" data-target="#confirmationModal{{ Str::slug(basename($file)) }}">
                                        Remove
                                    </button>
                                </div> --}}

                                    <!-- Confirmation Modal -->
                                    {{-- <div class="modal fade" id="confirmationModal{{ $file }}" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true"> --}}
                                    <div class="modal fade" id="confirmationModal{{Str::slug(basename($filee['path'] )) }}" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                          <div class="modal-content">
                                              <div class="modal-header">
                                                  <h5 class="modal-title" id="confirmationModalLabel">Confirm Deletion</h5>
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                  </button>
                                              </div>
                                              <div class="modal-body">
                                                  Are you sure you want to delete this file?
                                              </div>
                                              <div class="modal-footer">
                                                  <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cancel</button>
                                                  {{-- <form method="POST" action="{{ route('formpost.deletefile', ['id' => $getRecord->id]) }}" style="display: inline;">                                                      @csrf
                                                        @csrf
                                                        @method('DELETE')
                                                      <input type="hidden" name="file" value="{{ $file }}"> --}}
                                                      <button class="btn btn-danger" data-postid="{{ $getRecord->id }}" data-filepath="{{ $filee['path'] }}" onclick="removeThisFile(this)">Delete</button>
                                                      {{-- </form> --}}

                                              </div>
                                          </div>
                                      </div>
                                    </div>
                                </li>
                            {{-- @endforeach --}}
                        </ul>
                    @else
                        <p>No files uploaded.</p>
                    @endif
            <div class="wrapper">

                <label for="files">Upload Document File:</label>

                <div class="input-group hdtuto control-group increment" >
                  <input type="file" name="files[]" class="myfrm form-control">
                  <div class="input-group-btn">
                      <button class="btn btn-success" type="button" onclick="addFileInput(this)">
                          <i class="fldemo glyphicon glyphicon-plus"></i>Add
                      </button>
                  </div>
              </div>
              <div class="clone hide">
                  <div class="hdtuto control-group lst input-group" style="margin-top:10px">
                      <input type="file" name="files[]" class="myfrm form-control">
                      <div class="input-group-btn">
                          <button class="btn btn-danger" type="button" onclick="removeFileInput(this)">
                              <i class="fldemo glyphicon glyphicon-remove"></i> Remove
                          </button>
                      </div>
                  </div>
              </div>
                <section class="uploaded-area"></section>
              </div>

      </div>
      <!-- /.card-body -->

      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Update</button>
        {{-- <button type="button" onclick="submitForm()">Update</button> --}}

      </div>
    </form>
  </div>

<style>
/* Style the select element */
.card .card-body .custom-select {
    /* display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    background-color: #e0ebb9;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; */
}

/* Style the select element when it's in focus */
.custom-select:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Style the dropdown arrow */
.custom-select::after {
    content: "\25BC"; /* Unicode character for a down arrow */
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
}

/* Style the dropdown options */
.custom-select option {
    background-color: #fff;
    color: #333;
}

/* Style the selected option */
.custom-select option:checked {
    background-color: #007bff;
    color: #fff;
}

</style>

<script>
console.log('JavaScript file loaded');

    flatpickr(".datetimepicker", {
        enableTime: true,
        dateFormat: "Y-m-d H:i", // Change this format according to your needs
    });


function addFileInput(element) {
  console.log('addFileInput file loaded');

      var clone = $(element).closest('.increment').clone();
      clone.find('input').val('');
      clone.insertAfter($(element).closest('.increment'));
      clone.find('.btn-success').html('<i class="fldemo glyphicon glyphicon-remove"></i> Remove').removeClass('btn-success').addClass('btn-danger').attr('onclick', 'removeFileInput(this)');
      clone.css('margin-top', '10px'); // Add margin to the new row
    }
    function removeFileInput(button) {
          // const parent = button.closest('.increment');
          // parent.remove();
          $(button).closest('.increment').remove();
      }

    function removeThisFile(button) {
        const filePath = button.getAttribute('data-filepath');
        const postID = button.getAttribute('data-postid');
        const modalID = button.getAttribute('data-modal-id');

        // Call the removeFile function with postID
        removeFile(modalID, postID, filePath);
    }

    function removeFile(modalID, postID, filePath) {
    console.log('removeThisFile function called');

    // const filePath = button.getAttribute('data-filepath');
    const encodedFilePath = encodeURIComponent(filePath); // Encode the file path
    const sanitizedFilePath = encodedFilePath.replace(/ /g, '_'); // Replace spaces with underscores

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    console.log(filePath);

    // Send a DELETE request via AJAX
    fetch(`/admin/formsubmissionpage/remove/${postID}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken, // Include your CSRF token if needed
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ file: filePath }),
    })
    // .then(response => response.json())
    // .then(data => {
    .then(response => {
        // if (data.message) {
        if (response.ok) {
            // console.log(filePath);

            // File was successfully removed

            // Handle the UI accordingly
            // For example, remove the file entry from the DOM
            const fileEntry = document.querySelector(`[data-filepath="${filePath}"]`);
            // dd(fileEntry);
            console.log('function called');
            if (fileEntry) {
                fileEntry.remove();
            }

             // Display a success message
            const successMessage = document.getElementById('success-message');
            successMessage.textContent = 'File removed successfully';
            successMessage.style.display = 'block';

            $(`[data-target="#${modalID}"]`).modal('hide');

            // // Hide the success message after 5 seconds (5000 milliseconds)
            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 10000); // Adjust the delay as needed

            // Close the modal
            // $('#confirmationModal' + filePath).modal('hide');
            // $(`[data-target="#${modalID}"]`).modal('hide');

            // Close the modal after a delay (e.g., 2 seconds)
            // setTimeout(function() {
            //     $(`[data-target="#${modalID}"]`).modal('hide');
            // }, 2000); // Adjust the delay as needed


            const currentUrl = window.location.href;
            window.location.href = currentUrl;

        } else {
            // Handle errors here
            console.log("notok");

            // Check the response status to determine the type of error
            if (response.status === 401) {
                // Unauthorized (e.g., user is not allowed to delete the file)
                alert('You are not authorized to delete this file.');
            } else if (response.status === 404) {
                // Not Found (e.g., the file or resource does not exist)
                alert('The file does not exist.');
            } else if (response.status === 500) {
                // Internal Server Error (e.g., a server-side error occurred)
                alert('An internal server error occurred.');
            } else {
                // Handle other error cases
                alert('An error occurred while deleting the file.');
            }
        }
    })
    .catch(error => {
    //   console.error('An error occurred:', error);
    //   // Handle network errors (e.g., no internet connection)
    //   alert('An error occurred while deleting the file: ' + error.message);

        console.error('An error occurred:', error);
        // Log the response from the server
        console.log('Server response:', error.response);
        // Handle network errors (e.g., no internet connection)
        alert('An error occurred while deleting the file: ' + error.message);
    });

}

    const form = document.querySelector("form"),
      fileInput = document.querySelector(".file-input"),
      progressArea = document.querySelector(".progress-area"),
      uploadedArea = document.querySelector(".uploaded-area");

form.addEventListener("click", () => {
  fileInput.click();
});

fileInput.onchange = ({ target }) => {
  let file = target.files[0];
  if (file) {
    let fileName = file.name;
    if (fileName.length >= 12) {
      let splitName = fileName.split('.');
      fileName = splitName[0].substring(0, 13) + "... ." + splitName[1];
    }
    uploadFile(file, fileName);
  }
};

function uploadFile(file, name) {
  let xhr = new XMLHttpRequest();
  let formData = new FormData();

  formData.append('file_data', file); // Append the file to the form data
  formData.append('_token', '{{ csrf_token() }}'); // Add the CSRF token

  xhr.open("POST", "{{ route('template.create') }}");

  xhr.upload.addEventListener("progress", ({ loaded, total }) => {
    let fileLoaded = Math.floor((loaded / total) * 100);
    let progressHTML = `<div class="progress-bar" style="width: ${fileLoaded}%"></div>`;
    progressArea.innerHTML = progressHTML;
    document.querySelector(".percent").innerText = `${fileLoaded}%`;
  });

  xhr.onreadystatechange = () => {
    if (xhr.readyState === 4 && xhr.status === 200) {
      progressArea.innerHTML = ""; // Clear the progress bar
      let uploadedHTML = `<div class="uploaded-file">${name}</div>`;
      uploadedArea.insertAdjacentHTML("beforeend", uploadedHTML);
    }
  };

  xhr.send(formData);
}

</script>


@endsection


