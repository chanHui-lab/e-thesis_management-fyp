@extends('student.studentpure')

@section('master_content')

<main>
    <h1 style = "padding-top: 20px; padding-bottom:20px"><i class="fas fa-pencil-alt" style="font-size:40px;margin-right:10px">
    </i>Edit Thesis Submission</h1>
    {{-- @php
                dd($submissionPostId);
    @endphp --}}
    <a style = "margin-bottom: 20px;" class="btn btn-primary" href="{{ route('stuThesisSubmission.details', ['submissionPostId' => $submissionPostId]) }}"> Back</a>
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
            <h3 class="card-title">Edit Thesis</h3>
        </div>
        {{-- <form method="POST" action="" enctype="multipart/form-data"> --}}

        <form method="POST" action="{{ route('thesisSubmission.update', ['thesisSubmissionId' => $getRecord->id]) }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="thesis_title"  class = "labelling">Title:</label>
                    <input type="text" id="thesis_title" name="thesis_title" class = "form-control" placeholder="Enter Title..." value="{{ $getRecord -> thesis_title }}"><br>

                </div>
                <div class="form-group">
                    <label for="thesis_abstract" class = "labelling">Abstract (to be displayed in PutraMas Repo):</label>
                    <textarea id="thesis_abstract" class="form-control" style="height: 150px" name="thesis_abstract" rows="4" cols="50" placeholder="thesis_abstract">{{ $getRecord->thesis_abstract }}</textarea><br>
                </div>

                <p>Current File:</p>
                @php
                    $files = json_decode($getRecord->thesis_file, true);
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

                {{-- <div class="wrapper"> --}}
                <div class="form-group" style="padding-top: 20px">
                    <label for="thesis_file">Upload Thesis File (Must in PDF)</label>
                    <div class="container ">
                        <div class="row">
                            <div class="col-md-12 pl-0">
                                <div class="input-group hdtuto control-group increment ">
                                    <input type="file" name="thesis_file" class="myfrm form-control">
                                    <div class="input-group-btn">
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div>
                    {{-- end remove fileinput --}}
                </div>

                <br>
                <div class="form-group">
                    <label>Type of Thesis:</label>
                    <div class="custom-checkbox-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="web_development" name="thesis_type[]" value="web_development" {{ in_array('web_development', $selectedThesisTypes) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="web_development">Website Development</label>
                        </div>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="mobile_development" name="thesis_type[]" value="mobile_development" {{ in_array('mobile_development', $selectedThesisTypes) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="mobile_development">Mobile App Development</label>
                        </div>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="machine_learning" name="thesis_type[]" value="machine_learning" {{ in_array('machine_learning', $selectedThesisTypes) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="machine_learning">Machine Learning</label>
                        </div>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="data_analytics" name="thesis_type[]" value="data_analytics" {{ in_array('data_analytics', $selectedThesisTypes) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="data_analytics">Data Analytics</label>
                        </div>
                        <!-- Add more checkboxes as needed -->
                    </div>
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
                url: '{{ route("thesisSubmission.remove-file", $getRecord->id) }}',
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
