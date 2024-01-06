@extends('admin.template_page.adminpure')

@section('master_content')

<main>
<h1 style = "padding-top: 20px; padding-bottom:10px">Create Form Template</h1>

<a href="{{ route('formtemplate.index') }}" class="btn btn-primary" style = "margin-bottom:20px">Back</a>

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
      <h3 class="card-title">Create</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    @php
        $storerouteName = (auth()->user()->role_as == 0) ? 'template.store' : 'lecttemplate.store';
    @endphp

    <form method="post" action="{{ route('template.store')}}" enctype="multipart/form-data">

        @csrf
        <div class="card-body">
            <input type="hidden" name="section" value="form">

        <div class="form-group">

            <label for="file_name"  class = "labelling">Template Name:</label>
            <input type="text" id="file_name" name="file_name" class = "form-control" placeholder=" Name" ><br>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" class = "form-control labelling" style="height: 150px" name="description" rows="4" cols="50" placeholder="Details" ></textarea><br>
        </div>
         <label for="file_data">Upload Document File:</label><br>

          <div class="container">
            <div class="row">
                <div class="col-md-12" style="padding: 0%">
                    {{-- <h4>File Upload</h4> --}}
                    <div class="input-group hdtuto control-group increment">
                        <input type="file" name="file_data[]" class="myfrm form-control">
                        <div class="input-group-btn">
                            <button class="btn btn-success" type="button" onclick="addFileInput(this)">
                                <i class="fldemo glyphicon glyphicon-plus"></i>Add
                            </button>
                        </div>
                    </div>
                    <div class="clone hide">
                        <div class="hdtuto control-group lst input-group" style="margin-top:10px">
                            <input type="file" name="file_data[]" class="myfrm form-control">
                            <div class="input-group-btn">
                                <button class="btn btn-danger" type="button" onclick="removeFileInput(this)">
                                    <i class="fldemo glyphicon glyphicon-remove"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="col-sm-6">
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
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;" id="status" name="status" >
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

</form>
</div>
</main>
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





