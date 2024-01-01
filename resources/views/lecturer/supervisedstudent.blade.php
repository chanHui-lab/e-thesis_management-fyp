@extends('admin.template_page.adminpure')

@section('master_content')

<main>

    <h1><i class="fas fa-user-graduate" style="font-size: 35px; margin-right:20px;"></i>Supervisors and Students</h1>

    <div class="row">
        <div class="col-12 mt-4" style="padding: 10px; float: right;">
          <div class="card">

            <div class="card-body">
                <h5>Currently the student you're supervising:</h5>

                <table id="example1" class="table table-bordered table-striped">
                    <thead class="custom-thead">
                        <tr>
                            <th>No.</th>
                            <th>Matric Number</th>
                            <th>Student's Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1; // Initialize a counter variable
                        @endphp
                        @foreach ($mystudent as $student)
                            <tr>
                                <td>{{ $counter++ }}</td>
                                <td>{{ $student->matric_number }}</td>
                                <td>{{ $student->user->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
          </div>
        </div>
    </div>
    <h2 style="margin-top:20px;">Assigned Students</h2>
    <div class="row">
        <div class="col-12" style="padding: 10px; float: right;">
          <div class="card">

            <div class="card-body">

                <table id="example1" class="table table-bordered table-striped">
                    <thead class="custom-thead">
                        <tr>
                            <th>No.</th>
                            <th>Matric Number</th>
                            <th>Student's Name</th>
                            <th>Supervisor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1; // Initialize a counter variable
                        @endphp
                @foreach ($assignedStudents as $student)
                    <tr>
                        <td>{{ $counter++ }}</td>
                        <td>{{ $student->matric_number }}</td>
                        <td>{{ $student->user->name }}</td>
                        <td>{{ $student->supervisor->user->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div></div></div>

<h2 style="margin-top:20px;">All Lecturers</h2>

<div class="row">
    <div class="col-12" style="padding: 10px; float: right;">
      <div class="card">

        <div class="card-body">

            <table id="example3" class="table table-bordered table-striped">
                <thead class="custom-thead">
                    <tr>
                        <th>No.</th>
                        <th>Lecturer's Name</th>
                        <th>Supervised Student's Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $counter = 1; // Initialize a counter variable
                    @endphp
            @foreach ($advisors as $lecturer)
                <tr>
                    <td>{{ $counter++ }}</td>
                    <td>{{ $lecturer->user->name }}</td>
                    {{-- <td>{{ $lecturer->student->user->name }}</td> --}}
                    {{-- <td>
                        @foreach ($lecturer->student as $student)
                            {{ $student->user->name }}
                        @endforeach
                    </td> --}}
                    <td class="{{ $lecturer->student->isEmpty() ? 'text-muted' : '' }}">
                        @if ($lecturer->student->isNotEmpty())
                            {{ $lecturer->student->pluck('user.name')->implode(', ') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $lecturer->user->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
</div>
</div>

</main>
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

      // adjust the datatable
      $("#example3").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        columnDefs: [
              { width: '10px', targets: [0] }, // Adjust the width of the first column (index 0)
              { width: '10px', targets: [1] }, // Adjust the width of the first column (index 0)
              { width: '50px', targets: [2] },
              { width: '50px', targets: [3] },

          ]
      }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');

    });

  </script>
@endsection