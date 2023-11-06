@extends('admin.template_page.adminpure')

@section('master_content')
<main>
    <div class="row">
        <div style = "padding-top: 20px;" class="col-lg-12">
            <a class="btn btn-primary back-button" href="{{ route('formpost.index') }}">Back</a>

            <div class=" titleforform">
                <h2 style = "padding-top: 10px;"  >{{ $submissionPost->title }}</h2>
                <h6>(Total: {{ $formSubmissions -> count() }})</h6>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    {{-- <section class="content"> --}}
        {{-- <div class="container-fluid"> --}}
          <div class="row">
            <div class="col-12" style="padding: 10px; float: right;">
              <div class="card">
                {{-- <div class="card-header">
                  <h3 class="card-title">All Form Templates</h3>
                </div> --}}
                <!-- /.card-header -->

                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                    <tr>
                        <th>No.</th>
                        <th>Student </th>
                        <th>Student name</th>
                        <th>Matric Number </th>
                        <th>Form Title </th>
                        <th>Status</th> <!-- Add the status column -->
                        <th>Deadline</th>
                        <th width="300px">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1; // Initialize a counter variable
                        @endphp

                    @foreach ($students as $submission)
                    <tr>
                        {{-- <td>{{ $counter++ }}</td> <!-- Increment and display the counter -->
                        <td>{{ $submission->student->stu_id }}</td>
                        <td>{{ $submission->student->user->name }}</td>
                        <td>{{ $submission->student->matric_number }}</td>
                        <td>{{ $submission->form_title  }}</td> --}}

                        <td>{{ $counter++ }}</td>
                        <td>{{ $submission->stu_id }}</td>
                        <td>{{ $submission->student_name }}</td>
                        <td>{{ $submission->matric_number }}</td>
                        @php
                            $formSubmission = $formSubmissions->where('student_id', $submission->stu_id)->first();
                        @endphp
                        <td>{{ $formSubmission ? $formSubmission->form_title : 'N/A' }}</td>
                        <td>
                            @if ($formSubmission)
                                Submitted
                            @else
                                Pending
                            @endif
                        </td>
                        {{-- <td>
                            @php
                                $formFilesArray = json_decode($submission->form_files, true);
                            @endphp

                            @if (is_array($formFilesArray) && count($formFilesArray) > 0)
                                Submitted
                            @else
                                Pending
                            @endif
                        </td> --}}

                        <td>
                            @if ($submissionPost->submission_deadline <= now())
                                Deadline Exceeded
                            @else
                                Deadline Not Exceeded
                            @endif
                        </td>

                        {{-- <td class="{{ $postform->remainingTime->invert === 1 ? "red-bg" : "green-bg" }}">
                            {{ $postform->remainingTime->invert === 1 ? 'Deadline passed' : $postform->remainingTime->format('%d days, %h hours, %i minutes') }}
                        </td> --}}

                        <td>

                            {{-- it works but doiesnt ahve show button for the pending submission one --}}
                            {{-- @if ($formSubmission)
                                <a href="{{ route('formpost.show', ['submissionPostId' => $submissionPost->id, 'formSubmissionId' => $formSubmission->id]) }}" class="btn btn-primary">
                            <i class="fas fa-folder">
                                </i> Show
                                </a>
                            @endif --}}

                            <a href="{{ $formSubmission ? route('formpost.show', ['submissionPostId' => $submissionPost->id, 'formSubmissionId' => $formSubmission->id]) : '#' }}" class="btn btn-primary {{ !$formSubmission ? 'btn-disabled' : '' }}" @if (!$formSubmission) disabled @endif>
                                <i class="fas fa-folder"></i> Show
                            </a>

</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
          <!-- /.card-body -->
            {{-- {{ $post->links() }} --}}
        </div>
            </div></div></div></section></main>
        <!-- /.card -->

<style>
    .red-bg {
        background-color: rgb(255, 144, 144);
        color: rgb(0, 0, 0); /* You can set text color for better visibility */
    }

    /* Green background for deadlines with remaining time */
    td.green-bg {
        background-color: lightgreen !important;
    }
    /* for the SHOW button in viewAll balde */
    .btn-disabled {
        background-color: grey; /* You can customize the background color */
        cursor: not-allowed; /* Change the cursor style to indicate it's not clickable */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteLinks = document.querySelectorAll('.delete-link');

        deleteLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                const confirmDelete = window.confirm('Are you sure you want to delete this submission post?');

                if (confirmDelete) {
                    window.location.href = link.getAttribute('href');
                }
            });
        });
    });
</script>

<!-- jQuery -->
{{-- <script src="../../plugins/jquery/jquery.min.js"></script> --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->

<script src={{ asset('./plugins/datatables/jquery.dataTables.js') }}></script>
<script src={{ asset('./plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}></script>
<script src={{ asset('./plugins/datatables-responsive/js/dataTables.responsive.min.js') }}></script>
<script src={{ asset('./plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}></script>
<script src={{ asset('./plugins/datatables-buttons/js/dataTables.buttons.min.js') }}></script>
<script src={{ asset('./plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}></script>
<script src={{ asset('./plugins/jszip/jszip.min.js') }}></script>
<script src={{ asset('./plugins/pdfmake/pdfmake.min.js') }}></script>
<script src={{ asset('./plugins/pdfmake/vfs_fonts.js') }}></script>
<script src={{ asset('./plugins/datatables-buttons/js/buttons.html5.min.js') }}></script>
<script src={{ asset('./plugins/datatables-buttons/js/buttons.print.min.js') }}></script>
<script src={{ asset('./plugins/datatables-buttons/js/buttons.colVis.min.js') }}></script>

<script>
  $(function () {

    // adjust the datatable
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
      columnDefs: [
            { width: '10px', targets: [0] }, // Adjust the width of the first column (index 0)
            { width: '10px', targets: [1] }, // Adjust the width of the first column (index 0)
            { width: '50px', targets: [2] },
            { width: '50px', targets: [3] },
            { width: '50px', targets: [4] }

        ]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });

</script>
@endsection