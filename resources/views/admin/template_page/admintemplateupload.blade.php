@extends('admin.template_page.adminpure')

@section('master_content')
<main>
    <div class="row">
        <div class="col-lg-12">
            <div class=" titleforform">
                <h2>Form Templates</h2>
                <h6>(Total: {{ $template -> total() }})</h6>
            </div>
            <div class="float-right">
                <a class="btn btn-success" href="{{ route('template.create') }}"> Upload New Form Template</a>
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

    <table class="table table-bordered">
        <tr>
            <th>No.</th> <!-- Numbering column -->
            <th>Template Name</th>
            <th>Description for Students</th>
            <th>File Data</th>
            <th>Status</th>
            {{-- <th>Template file</th> --}}
            {{-- <th>Submission Deadline</th> --}}
            <th width="300px">Action</th>
        </tr>
        {{-- we can access the template table --}}
        @php
            $counter = 1; // Initialize a counter variable
        @endphp

        @foreach ($template as $templateform)
        <tr>
            <td>{{ $counter++ }}</td> <!-- Increment and display the counter -->
            <td>{{ $templateform->file_name }}</td>
            <td>{{  $templateform->description  }}</td>
            <td><a href="{{ asset('storage/' . $templateform->file_data) }}" download>
                {{ str_replace('upload/templates/', '', $templateform->file_data) }}
            </a></td>
            <td>
                @if ($templateform->status == '1')
                    <span class="status-visible">Visible</span>
                @else
                    <span class="status-hidden">Hidden</span>
                @endif
            </td>

            <td>
                <form action="{{ route('template.destroy',$templateform->id) }}" method="POST">

                    <a class="btn btn-info" href="{{ route('template.show',$templateform->id) }}">Show</a>
                    {{-- <a class="btn btn-primary" href="{{url('/view',$templateform->id)}}"">View</a> --}}

                    {{-- i wrong liap i store dao the file path in this variable --}}
                    {{-- <a class="btn btn-primary" href="{{route('file.download', $templateform->pdf_file_for_students)}}" target="_blank">Download</a> --}}

                    {{-- <a href="{{url('/download',$templateform->pdf_file_for_students)}}">Download</a> --}}
                    {{-- <a href="{{ asset('storage/' . $templateform->pdf_file_for_students) }}" target="_blank">Download File</a> --}}

                    {{-- <a href="{{ route('products.downloadFile', $templateform->pdf_file_for_students) }}">Download PDF</a> --}}

                    {{-- 3rd --}}
                    {{-- <a href="{{ route('products.downloadFile', $templateform->id) }}">Download</a> --}}

                    {{-- <a href="{{ route('products.download', $templateform->pdf_file_for_students) }}" target="_blank">Download File</a> --}}

                    {{-- <a href="{{ route('products.downloadFile', $templateform->id) }}" target="_blank">Download File</a> --}}

                    <a class="btn btn-primary" href="{{ route('template.edit',$templateform->id) }}">Edit</a>

                    {{-- delete parteu --}}
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $templateform->id }}">Delete</button>

                    <div class="modal fade" id="deleteModal{{ $templateform->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this template?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        @csrf
                                        @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    {{-- @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $templateform->id }}">Delete</button> --}}

                    {{-- <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModal{{ $templateform->id }}">Confirm Deletion</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this template?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <a href="{{ route('template.destroy', $templateform->id) }}" class="btn btn-danger">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </form>
            </td>
        </tr>
        <!-- Delete Confirmation Modal -->

        @endforeach
    </table>

    {{-- <section class="content"> --}}
        {{-- <div class="container-fluid"> --}}
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">All Form Templates</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                    <tr>
                      <th>No.	</th>
                      <th>Template Name</th>
                      <th>Description for Students</th>
                      <th>File Data</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php
                        $counter = 1; // Initialize a counter variable
                    @endphp

                    @foreach ($template as $templateform)
                    <tr>
                        <td>{{ $counter++ }}</td> <!-- Increment and display the counter -->
                        <td>{{ $templateform->file_name }}</td>
                        <td>{{  $templateform->description  }}</td>
                        <td><a href="{{ asset('storage/' . $templateform->file_data) }}" download>
                            {{ str_replace('upload/templates/', '', $templateform->file_data) }}
                        </a></td>
                        <td>
                            @if ($templateform->status == '1')
                                <span class="status-visible">Visible</span>
                            @else
                                <span class="status-hidden">Hidden</span>
                            @endif
                        </td>

                        <td>
                            <form action="{{ route('template.destroy',$templateform->id) }}" method="POST">

                                <a class="btn btn-primary btn-sm" href="{{ route('template.show',$templateform->id) }}">
                                    <i class="fas fa-folder">
                                    </i>
                                    View
                                </a>
                                {{-- <a class="btn btn-info" href="{{ route('template.show',$templateform->id) }}">Show</a> --}}


                                {{-- <a class="btn btn-primary" href="{{url('/view',$templateform->id)}}"">View</a> --}}

                                {{-- i wrong liap i store dao the file path in this variable --}}
                                {{-- <a class="btn btn-primary" href="{{route('file.download', $templateform->pdf_file_for_students)}}" target="_blank">Download</a> --}}

                                {{-- <a href="{{url('/download',$templateform->pdf_file_for_students)}}">Download</a> --}}
                                {{-- <a href="{{ asset('storage/' . $templateform->pdf_file_for_students) }}" target="_blank">Download File</a> --}}

                                {{-- <a href="{{ route('products.downloadFile', $templateform->pdf_file_for_students) }}">Download PDF</a> --}}

                                {{-- 3rd --}}
                                {{-- <a href="{{ route('products.downloadFile', $templateform->id) }}">Download</a> --}}

                                {{-- <a href="{{ route('products.download', $templateform->pdf_file_for_students) }}" target="_blank">Download File</a> --}}

                                {{-- <a href="{{ route('products.downloadFile', $templateform->id) }}" target="_blank">Download File</a> --}}

                                {{-- <a class="btn btn-primary" href="{{ route('template.edit',$templateform->id) }}">Edit</a> --}}
                                <a class="btn btn-info btn-sm" href="{{ route('template.edit',$templateform->id) }}">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                    Edit
                                </a>
                                {{-- delete parteu --}}
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{ $templateform->id }}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>

                                <div class="modal fade" id="deleteModal{{ $templateform->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this template?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    @csrf
                                                    @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                                {{-- @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $templateform->id }}">Delete</button> --}}

                                {{-- <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModal{{ $templateform->id }}">Confirm Deletion</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this template?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <a href="{{ route('template.destroy', $templateform->id) }}" class="btn btn-danger">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            </form>
                        </td>
                    </tr>
                    <!-- Delete Confirmation Modal -->
                @endforeach
                </tbody>
                </table>
          </div>
          <!-- /.card-body -->
        </div>
            </div></div></div></section></main>
        <!-- /.card -->
    {{ $template->links() }}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteLinks = document.querySelectorAll('.delete-link');

        deleteLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                const confirmDelete = window.confirm('Are you sure you want to delete this template?');

                if (confirmDelete) {
                    window.location.href = link.getAttribute('href');
                }
            });
        });
    });
</script>

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>

<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->

<script src={{ asset('./plugins/datatables/jquery.dataTables.min.js') }}></script>
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

{{-- <script src={{ asset('./dist/js/adminlte.min.js') }}></script> --}}

{{-- <script src="../../dist/js/adminlte.min.js"></script> --}}
<!-- AdminLTE for demo purposes -->
{{-- <script src="../../dist/js/demo.js"></script> --}}
<!-- Page specific script -->
<script>
  $(function () {

    // adjust the datatable
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
      columnDefs: [
            { width: '10px', targets: [0] }, // Adjust the width of the first column (index 0)
            { width: '10px', targets: [1] }, // Adjust the width of the first column (index 0)
            { width: '30px', targets: [2] },
            { width: '30px', targets: [3] }

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