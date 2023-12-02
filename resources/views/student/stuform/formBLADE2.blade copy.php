@extends('student.studentpure')

@section('master_content')
<main>
    <h1 style = "padding-top: 20px; padding-bottom:20px">Edit Form Template</h1>
    <a style = "margin-bottom: 20px;" class="btn btn-primary" href="{{ route('formpost.index') }}"> Back</a>
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

    {{-- <div id="success-message" class="alert alert-success" style="display: none; color: green;"></div> --}}

    <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Edit</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        {{-- @if ($getRecord) --}}

        <form method="POST" action="{{ route('formSubmission.update', ['formSubmissionId' => $getRecord->id]) }}" enctype="multipart/form-data">

            @csrf
            {{-- @method('PUT') --}}

            <div class="card-body">
            <div class="form-group">
                <label for="form_title"  class = "labelling">Title:</label>
                <input type="text" id="form_title" name="form_title" class = "form-control" placeholder= "Title" value="{{ $getRecord -> form_title }}"><br>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" class="form-control" style="height: 150px" name="description" rows="4" cols="50" placeholder="description">{{ $getRecord->description }}</textarea><br>
              </div>

                <p>Current File:</p>

                @php
                    $files = json_decode($getRecord->form_files, true);

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
                    {{-- @foreach ($files as $filee) --}}
                    @foreach ($files as $index => $filee)

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
                                <button type="button" class="btn btn-danger remove-file-btn" data-toggle="modal" data-target="#confirmationModal{{ $index }}" data-form-id="{{ $getRecord->id }}" data-file-index="{{ $index }}">
                                    Remove
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="confirmationModal{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel{{ $index }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmationModalLabel{{ $index }}">Confirm File Removal</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to remove this file?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-danger" id="confirmRemoveBtn" data-form-id="{{ $getRecord->id }}" data-file-index="{{ $index }}">
                                            Remove</button>
                                        {{-- <button type="button" class="btn btn-danger" id="confirmRemoveBtn">
                                            Remove</button> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
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
      {{-- @endif --}}
    </div>

</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
            var formIdToRemove, fileIndexToRemove;

            $('.remove-file-btn').click(function () {
                formIdToRemove = $(this).data('form-id');
                fileIndexToRemove = $(this).data('file-index');
                console.log("remove clicked ",formIdToRemove, fileIndexToRemove);
            });
            // Handle "Confirm Remove" button click
            // $('#confirmationModal').on('show.bs.modal', function (event) {
            //     var button = $(event.relatedTarget);
            //     formIdToRemove = button.data('form-id');

            //     // Log the values for debugging
            //     console.log("Remove clicked:", formIdToRemove, fileIndexToRemove);
            // });

            $('#confirmRemoveBtn').click(function () {
                var confirmedFormId = $(this).data('form-id');
                var confirmedFileIndex = $(this).data('file-index');

                console.log("remove clicksdsed ",confirmedFormId, confirmedFileIndex);

                if (confirmedFormId !== undefined && confirmedFileIndex !== undefined) {
                    formIdToRemove = confirmedFormId;
                    fileIndexToRemove = confirmedFileIndex;                    // Make the AJAX request for file removal
                        $.ajax({
                        url: '/student/form/submission/remove-file/' + formIdToRemove + '/' + fileIndexToRemove,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            console.log(response);  // Log the response for debugging

                            if (response.success) {
                                // Handle success, update the UI as needed
                                console.log('File removed successfully');
                            } else {
                                // Handle error, show an alert or message
                                // console.error('Error removing file. Server response:', response);
                                console.error(response.error);

                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Error removing file:', error);
                        }
                    });

                    // Close the modal
                    $('#confirmationModal').modal('hide');
                }
            });
        });
    function addFileInput(element) {
      console.log('addFileInput file loaded');

          var clone = $(element).closest('.increment').clone();
          clone.find('input').val('');
          clone.insertAfter($(element).closest('.increment'));
          clone.find('.btn-success').html('<i class="fldemo glyphicon glyphicon-remove"></i> Remove').removeClass('btn-success').addClass('btn-danger').attr('onclick', 'removeFileInput(this)');
          clone.css('margin-top', '10px'); // Add margin to the new row
        }
        const form = document.querySelector("form"),
          fileInput = document.querySelector(".file-input"),
          progressArea = document.querySelector(".progress-area"),
          uploadedArea = document.querySelector(".uploaded-area");

    form.addEventListener("click", () => {
      fileInput.click();
    });

    // fileInput.onchange = ({ target }) => {
    //   let file = target.files[0];
    //   if (file) {
    //     let fileName = file.name;
    //     if (fileName.length >= 12) {
    //       let splitName = fileName.split('.');
    //       fileName = splitName[0].substring(0, 13) + "... ." + splitName[1];
    //     }
    //     uploadFile(file, fileName);
    //   }
    // };

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