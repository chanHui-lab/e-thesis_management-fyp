@extends('admin.template_page.adminpure')

@section('master_content')
<main>
    <div class="row">
        <div class="col-lg-12">
            <div class=" titleforform">
                <h2 style = "padding-top: 20px;">Form Submission Post</h2>
                <h6>(Total: {{ $post -> total() }})</h6>
            </div>
            <div class="float-right" style = "padding-bottom: 10px;">
                <a class="btn btn-success" href="{{ route('formpost.create') }}"><i class="fa fa-upload" style="margin-right: 5px;"></i> Upload New Form Submission Post</a>
                {{-- <a class="btn btn-success" href="{{url("admin/adminpage/createform") }}"> Upload New Form Template</a> --}}
                {{-- the url will not show this, instead of the one in web.php --}}

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
                        <th>No.</th> <!-- Numbering column -->
                        <th>Title</th>
                        <th>Description for Students</th>
                        <th>Submission Deadline</th>
                        <th>Remaining Time</th>
                        {{-- <th>No of Students Submitted</th> --}}
                        <th width="300px">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php
                        $counter = 1; // Initialize a counter variable
                    @endphp

                    @foreach ($post as $postform)
                    <tr>
                        <td>{{ $counter++ }}</td> <!-- Increment and display the counter -->
                        <td>{{ $postform->title }}</td>
                        <td>{{ $postform->description  }}</td>
                        <td>{{ $postform->submission_deadline }}</td>
                        {{-- <td><a href="{{ asset('storage/' . $templateform->file_data) }}" download>
                            {{ str_replace('upload/templates/', '', $templateform->file_data) }}
                        </a></td> --}}
                        {{-- <td>{{ $submission->remainingTime->format('%d days, %h hours, %i minutes') }}</td> --}}

                        {{-- <td>{{ $postform->remainingTime->format('%d days, %h hours, %i minutes')}}</td> --}}
                        <td class="{{ $postform->remainingTime->invert === 1 ? "red-bg" : "green-bg" }}">
                            {{ $postform->remainingTime->invert === 1 ? 'Deadline passed' : $postform->remainingTime->format('%d days, %h hours, %i minutes') }}
                        </td>

                        <td>
                            <form action="{{ route('formpost.destroy',$postform->id) }}" method="POST">

                                <a class="btn btn-primary btn-sm" href="{{ route('template.show',$postform->id) }}">
                                    <i class="fas fa-folder">
                                    </i>
                                    Show
                                </a>

                                {{-- <a href="{{ route('submission-post.view-submissions', ['submissionPostId' => $submissionPost->id]) }}" class="btn btn-primary">View All Submissions</a> --}}
                                <a class="btn btn-primary btn-sm" href="{{ route('formpost.showAll', ['submissionPostId' => $postform->id]) }}">
                                {{-- <a class="btn btn-primary btn-sm" href="{{ route('formpost.showAll',$postform->id) }}"> --}}
                                    <i class="fas fa-folder">
                                    </i>
                                    View All Submission
                                </a>

                                <a class="btn btn-info btn-sm" href="{{ route('formpost.edit',$postform->id) }}">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                    Edit
                                </a>

                                {{-- delete parteu --}}
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{ $postform->id }}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>

                                <div class="modal fade" id="deleteModal{{ $postform->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this submission post?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    @csrf
                                                    @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
          <!-- /.card-body -->
            {{ $post->links() }}
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