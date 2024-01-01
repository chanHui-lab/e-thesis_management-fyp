@extends('student.studentpure')

@section('master_content')

<main>
    <h1 style = "padding-top: 20px; padding-bottom:20px"><i class="fas fa-pencil-alt" style="font-size:40px;margin-right:10px">
    </i>Edit Form Submission</h1>
        {{-- @php
                dd($submissionPostId);
    @endphp --}}
    <a style = "margin-bottom: 20px;" class="btn btn-primary" href="{{ route('stuFormSubmission.details', ['submissionPostId' => $submissionPostId]) }}"> Back</a>
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

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Edit</h3>
        </div>
        <form method="POST" action="{{ route('formSubmission.update', ['formSubmissionId' => $getRecord->id]) }}" enctype="multipart/form-data">
            @csrf
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
                <table class="table table-bordered" style="width: 900px;">
                    <thead>
                        <tr>
                            <th class="wider-column">File Name</th>
                            <th style="width: 100px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($files as $filee)
                        <tr>
                            <td>
                                {{-- {{ basename($filee['path']) }} --}}
                                <a href="{{ asset('storage/' . $filee['path']) }}" target="_blank" download class="downloadfile-link">
                                    @if (Str::endsWith($filee['path'], '.pdf'))
                                    <i class="fa fa-file-pdf file-icon" style = "color:  rgb(255, 86, 86)"></i>

                                    @elseif (Str::endsWith($filee['path'], '.doc') || Str::endsWith($filee['path'], '.docx'))
                                    <i class="fa fa-file-word file-icon" style = "color: rgb(77, 144, 250)"></i>

                                    @else
                                    <i class="fa fa-solid fa-file file-icon" style = "color: rgb(77, 144, 250)"></i>
                                    @endif
                                    {{-- {{ substr($filee['path'], strpos($filee['path'], '_') + 1) }} --}}
                                    {{ basename($filee['path']) }}
                                </a >

                            </td>
                            {{-- <td> --}}
                                {{-- {{ \Carbon\Carbon::parse(\Storage::lastModified($file))->toDateTimeString() }} --}}
                                {{-- {{ $file['uploaded_at'] }} --}}
                                {{-- {{ $file['uploaded_at']->format('Y-m-d H:i:s') }} --}}
                            {{-- </td> --}}
                            <td>
                                <button type="button" class="btn btn-danger removeFileBtn" data-path="{{ $filee['path'] }}">Remove</button>
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
                                Are you sure you want to remove this file?
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
                    <label for="files">Upload Document File:</label>
                    <div class="input-group hdtuto control-group increment" >
                        <input type="file" name="files[]" class="myfrm form-control">
                        <div class="input-group-btn">
                            <button class="btn btn-success" type="button" onclick="addFileInput(this)">
                                <i class="fldemo glyphicon glyphicon-plus"></i>Add
                            </button>
                        </div>
                    </div>
                    {{-- end addFile --}}
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
                    {{-- end remove fileinput --}}
                </div>


            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>

</main>


<script>
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
                url: '{{ route("formSubmission.remove-file", $getRecord->id) }}',
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
</script>

@endsection
