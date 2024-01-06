@extends('admin.template_page.adminpure')

@section('master_content')
<main>
    <div class="row">
        <div class="col-lg-12">
            <div class=" titleforform">
                <h1><i style="font-size: 30px;margin-right:20px" class="fas fa-clipboard"></i>Thesis Templates</h1>
                <h5>(Total: {{ $template -> count() }})</h5>
            </div>
            <div class="float-right">
                <a style="margin-bottom: 10px;" class="btn btn-success btn-common" href="{{ route('lectthesistemplate.create') }}">
                    <i class="fa fa-upload iconie"></i>
                    Upload New Thesis Template
                </a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">All Thesis Templates</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead class="custom-thead">
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
                        <td>
                            @php
                                $files = json_decode($templateform->file_data, true);
                            @endphp
                            @foreach ($files as $file)
                            &#x2514;
                                @if (Str::endsWith($file['path'], '.pdf'))
                                    <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" download class="downloadfile-link">
                                        <i class="fa fa-file-pdf file-template" style = "color: rgb(255, 86, 86)"></i>
                                        {{ basename($file['path']) }}
                                    </a>
                                @elseif (Str::endsWith($file['path'], '.doc') || Str::endsWith($file['path'], '.docx'))
                                    <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" download class="downloadfile-link">
                                        <i class="fa fa-file-word file-template" style = "color: rgb(77, 144, 250)"></i>
                                        {{ basename($file['path']) }}
                                    </a>
                                @else
                                    <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" download class="downloadfile-link">
                                        <i class="fa fa-file file-template" style = "color: rgb(77, 144, 250)"></i>
                                        {{-- {{ basename($file) }} --}}
                                        {{ basename($file['path']) }}
                                    </a>
                                @endif
                                <br>
                            @endforeach
                        </td>


                        <td>
                            @if ($templateform->status == '1')
                                <span class="status-visible">Visible</span>
                            @else
                                <span class="status-hidden">Hidden</span>
                            @endif
                        </td>

                        <td>
                            <form action="{{ route('lecttemplate.destroy',$templateform->id) }}" method="POST">
                            @if ($templateform->lecturer_id == Auth::id())

                             <a class="btn btn-info btn-sm rounded-btn" href="{{ route('lectthesistemplate.edit',$templateform->id) }}">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                    Edit
                                </a>
                                {{-- delete parteu --}}
                                <button type="button" class="btn btn-danger btn-delete btn-sm rounded-btn" data-toggle="modal" data-target="#deleteModal{{ $templateform->id }}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>

                                <div class="modal popUpModal" id="deleteModal{{ $templateform->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
                                                <button type="button" class="btn btn-secondary rounded-btn" onclick="closeModal()">
                                                    Cancel</button>
                                                @csrf
                                                    @method('DELETE')
                                                <button type="submit" class="btn btn-danger rounded-btn">Delete</button>                                </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            {{-- </div> --}}
                            </form>
                        </td>
                    </tr>
                    <!-- Delete Confirmation Modal -->
                @endforeach
                </tbody>
                </table>
          </div>
          <!-- /.card-body -->
        {{ $template->links() }}
        </div>
            </div></div>

        </div>
        <!-- /.card -->
    </main>

<!-- jQuery -->
{{-- <script src="../../plugins/jquery/jquery.min.js"></script> --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
<style>
    .modal {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 20px;
    width: 100%;
    height: 100%;
    background-color: hsla(0, 0%, 82%, 0.729);
    z-index: 1005; /* Ensure it's above the overlay */
}
</style>
<script>
  $(function () {

    // $('#deleteModal{{ $templateform->id }}').modal('show').fadeIn();
        // Attach a click event to the delete button
        $('.btn-delete').on('click', function () {
            // Hide the modal backdrop
            $('.modal-backdrop').hide();
        });
    // adjust the datatable
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
      columnDefs: [
            { width: '0px', targets: [0] }, // Adjust the width of the first column (index 0)
            { width: '5px', targets: [1] }, // Adjust the width of the first column (index 0)
            { width: '5px', targets: [2] },
            { width: '10px', targets: [3] }
            { width: '5px', targets: [4] }
            { width: '20px', targets: [5] }

        ]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('.dt-buttons button').css('color', '#212529');
    console.log("buttons");

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