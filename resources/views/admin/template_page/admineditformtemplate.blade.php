@extends('admin.template_page.adminpure')

@section('master_content')

<main>
<h1 style = "padding-top: 20px; padding-bottom:20px">Edit Form Template</h1>
<div class="pull-right">
  <a class="btn btn-primary" href="{{ route('template.index') }}"> Back</a>
</div>
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

{{-- <div class = "containerr"> --}}
<!-- Create Post Form -->

<div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">Edit</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->

    {{-- <form method="post" action="{{ url ('admin/adminpage/admintemplateupload')}}" enctype="multipart/form-data"> --}}
    {{-- <form method="post" action="{{ route('template.update', $template->id])}}" enctype="multipart/form-data"> --}}
    <form method="post" action="" enctype="multipart/form-data">

        @csrf
        {{-- @method('PUT') --}}

        <div class="card-body">
        <div class="form-group">
            <label for="file_name"  class = "labelling">Template Name:</label>
            <input type="text" id="file_name" name="file_name" class = "form-control" placeholder=" Name" value="{{ $getRecord -> file_name }}"><br>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" class="form-control" style="height: 150px" name="description" rows="4" cols="50" placeholder="description">{{ $getRecord->description }}</textarea><br>
          </div>
        {{-- <div class="form-group"> --}}
         <label for="file_data">Upload Document File:</label><br>
                <div class="wrapper">
                  <div class ="dotted">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Browse File</p>
                  </div>
                  {{-- <p>Current File:
                    @if ($getRecord->file_data)
                      <a href="{{ asset('storage/' . $getRecord->file_data) }}" download>
                      {{ str_replace('upload/templates/', '', $getRecord->file_data) }}
                      </a>
                  </p>
                  @endif --}}
                  <p>Current File:</p>

                  @php
                      $files = json_decode($getRecord->file_data, true);
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
                        @foreach ($files as $file)
                            <tr>
                                <td>

                                    @if (Str::endsWith($file['path'], '.pdf'))
                                        <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" download class="downloadfile-link">
                                            <i class="fa fa-file-pdf file-icon" style = "color: rgb(255, 86, 86)"></i>
                                            {{ substr($file['path'], strpos($file['path'], '_') + 1) }}
                                        </a>
                                    @elseif (Str::endsWith($file['path'], '.doc') || Str::endsWith($file['path'], '.docx'))
                                        <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" download class="downloadfile-link">
                                            <i class="fa fa-file-word file-icon" style = "color: rgb(77, 144, 250)"></i>
                                            {{ substr($file['path'], strpos($file['path'], '_') + 1) }}
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" download class="downloadfile-link">
                                            <i class="fa fa-file file-icon" style = "color: rgb(77, 144, 250)"></i>
                                            {{-- {{ basename($file) }} --}}
                                            {{ substr($file['path'], strpos($file['path'], '_') + 1) }}
                                        </a>
                                    @endif
                                </td>
                                {{-- <td> --}}
                                    {{-- {{ \Carbon\Carbon::parse(\Storage::lastModified($file))->toDateTimeString() }} --}}
                                    {{-- {{ $file['uploaded_at'] }} --}}
                                    {{-- {{ $file['uploaded_at']->format('Y-m-d H:i:s') }} --}}
                                {{-- </td> --}}
                                <td>
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-modal-id="confirmationModal{{  $file['path'] }}" data-target="#confirmationModal{{ Str::slug(basename($file['path'] )) }}">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="modal fade" id="confirmationModal{{ Str::slug(basename($file['path'] )) }}" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
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
                            <button class="btn btn-danger" data-postid="{{ $getRecord->id }}" data-filepath="{{ is_array($file) ? $file['path'] : $file }}" onclick="removeThisFile(this)">Delete</button>
                            {{-- </form> --}}

                          </div>
                      </div>
                  </div>
                </div>
            {{-- </li> --}}
        {{-- @endforeach --}}
      {{-- </ul> --}}
      @else
      <p>No files uploaded.</p>
      @endif
                <input class="file-input" type="file" class="form-control" id="file_data" name="file_data" accept=".pdf,.doc,.docx">
              {{-- <section class="progress-area"></section>
                <div class="progress">
                    <div class="progress-bar" style="width: 0%"></div>
                    <span class="percent">0%</span>

                </div> --}}
                <section class="uploaded-area"></section>
            {{-- </div> --}}
              </div>
        <br>
        <div class="col-sm-6">
        {{-- <div class="form-group"> --}}
            <label for="status">Status:</label>
            <select class="custom-select"  style= "display: block;
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;" id="status" name="status"  value="{{ $getRecord -> status }}>
                <option value = "0"> Hidden </option>
                <option value = "1"> Visible </option>
            </select>
        </div>

      </div>
      <!-- /.card-body -->

      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
  </div>

@endsection

@section('scripts')

<script>
    flatpickr(".datetimepicker", {
        enableTime: true,
        dateFormat: "Y-m-d H:i", // Change this format according to your needs
    });
</script>

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





