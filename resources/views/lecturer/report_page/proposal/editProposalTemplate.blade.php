@extends('admin.template_page.adminpure')

@section('master_content')

<main>
<h1 style = "padding-top: 20px; padding-bottom:20px">Edit Proposal Template</h1>
<div class="pull-right">
  <a class="btn btn-primary" href="{{ route('lectproptemplate.index') }}" style = "margin-bottom:20px"> Back</a>
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

<!-- Edit Post Form -->

<div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">Edit</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->

    {{-- <form method="post" action="{{ url ('admin/adminpage/admintemplateupload')}}" enctype="multipart/form-data"> --}}
    <form method="post" action="{{ route('lecttemplate.updatew', ['id' => $getRecord->id]) }}" enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="card-body">
        <div class="form-group">
            <label for="file_name"  class = "labelling">Template Name:</label>
            <input type="text" id="file_name" name="file_name" class = "form-control" placeholder=" Name" value="{{ $getRecord -> file_name }}"><br>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" class="form-control" style="height: 150px" name="description" rows="4" cols="50" placeholder="description">{{ $getRecord->description }}</textarea><br>
          </div>

        <p>Current File:</p>

        @php
            $files = json_decode($getRecord->file_data, true);
        @endphp
    @if (is_array($files) && count($files) > 0)
    <table class="table table-bordered" style = "width: 900px; margin-bottom: 2rem;" >
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
                                {{ basename($file['path']) }}
                            </a>
                        @elseif (Str::endsWith($file['path'], '.doc') || Str::endsWith($file['path'], '.docx'))
                            <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" download class="downloadfile-link">
                                <i class="fa fa-file-word file-icon" style = "color: rgb(77, 144, 250)"></i>
                                {{ basename($file['path']) }}
                            </a>
                        @else
                            <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" download class="downloadfile-link">
                                <i class="fa fa-file file-icon" style = "color: rgb(77, 144, 250)"></i>
                                {{ basename($file['path']) }}
                            </a>

                        @endif
                    </td>
                    {{-- <td> --}}
                        {{-- {{ \Carbon\Carbon::parse(\Storage::lastModified($file))->toDateTimeString() }} --}}
                        {{-- {{ $file['uploaded_at'] }} --}}
                        {{-- {{ $file['uploaded_at']->format('Y-m-d H:i:s') }} --}}
                    {{-- </td> --}}
                    <td>
                        <button type="button" class="btn btn-danger removeFileBtn" data-path="{{ $file['path'] }}">Remove</button>
                        {{-- <button type="button" class="btn btn-danger" data-toggle="modal" data-modal-id="confirmationModal{{  $file['path'] }}" data-target="#confirmationModal{{ Str::slug(basename($file['path'] )) }}">
                            Remove
                        </button> --}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Confirm Remove File Modal -->
    <div class="modal fade" id="confirmRemoveFileModal" tabindex="-1" role="dialog" aria-labelledby="confirmRemoveFileModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmRemoveFileModalLabel">Confirm Remove File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to remove this file ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="removeFileBtn">Remove</button>
                </div>
            </div>
        </div>
    </div>
      @else
      <p>No files uploaded.</p>
      @endif
      <div class="wrapper">

        <label for="file_data">Upload Document File:</label>

        <div class="input-group hdtuto control-group increment" style="width: 90%;">
            <input type="file" name="file_data[]" class="myfrm form-control">
            <div class="input-group-btn">
              <button class="btn btn-success add-file" type="button" onclick="addFileInput(this)">
                  <i class="fldemo glyphicon glyphicon-plus"></i>Add
              </button>
          </div>
      </div>
      <div class="clone hide">
          <div class="hdtuto control-group lst input-group" style="margin-top:15px;width: 90%;">
              <input type="file" name="file_data[]" class="myfrm form-control">
              <div class="input-group-btn">
                  <button class="btn btn-danger" type="button" onclick="removeFileInput(this)">
                      <i class="fldemo glyphicon glyphicon-remove"></i> Remove
                  </button>
              </div>
          </div>
      </div>
        <section class="uploaded-area"></section>
      </div>

      <br>
      <div class="col-sm-6"  style= "padding-left:0%">
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

console.log('JavaScript file loaded');
    $(document).ready(function () {

        // Show confirmation modal for the specific file when Remove File button is clicked
        $('.removeFileBtn').click(function () {
            console.log("buttoncldikde.");
            // Set the data-path attribute to the selected file path
            var filePath = $(this).data('path');
            console.log(filePath);

            $('#confirmRemoveFileModal').data('filePath', filePath).modal('show');
        });

         // AJAX request when Remove File button is clicked inside the modal
         $('#removeFileBtn').click(function () {
            var filePath = $('#confirmRemoveFileModal').data('filePath');
            console.log(filePath);

            $.ajax({
                url: '{{ route("lecttemplate.remove-file", $getRecord->id) }}',
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: { path: filePath }, // Pass the file path as data
                success: function (response) {
                    // Handle success, e.g., show a success message
                    console.log(response.message);
                    // Close the modal if needed
                    // Find and remove the corresponding row from the table
                    var fileName = filePath.split('/').pop(); // Extract the file name from the path
                    $('table tbody').find("td:contains('" + fileName + "')").closest('tr').remove();

                    $('#confirmRemoveFileModal').modal('hide');
                },
                error: function (error) {
                    // Handle error, e.g., show an error message
                    console.log(error.responseText);
                }
            });
        });
    });
    const form = document.querySelector("form"),
      fileInput = document.querySelector(".file-input"),
      progressArea = document.querySelector(".progress-area"),
      uploadedArea = document.querySelector(".uploaded-area");

      function addFileInput(element) {
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

  xhr.open("POST", "{{ route('lecttemplate.create') }}");

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