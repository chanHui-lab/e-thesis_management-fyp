@extends('admin.template_page.adminpure')

@section('master_content')
<main>
    <div class="row">
        <div class="col-lg-12">
            <div class=" titleforform">
                <h2><i style="font-size: 25px;margin-right:10px" class="fas fa-clipboard">
                    </i>
                    Evaluation Forms
                </h2>
                {{-- <h6>(Total: {{ $template -> total() }})</h6> --}}
            </div>
            <div class="float-right">
                {{-- <a style="margin-bottom: 10px;" class="btn btn-success rounded-btn" href="{{ route('template.create') }}">
                    <i class="fa fa-upload iconie"></i>
                    Upload New Evaluation Form
                </a> --}}
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
                    <h3 class="card-title">All Evaluation Forms</h3>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        kashshkdfjsfdjksfdlkfds
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="printform">
        <!-- Your form HTML here -->
        {{-- <form method="post" action="/form">
            <!-- Form fields go here -->
            @csrf
            <button type="submit">Submit</button>
        </form> --}}
        isdusfvkhfsidsidsoi
    </div>
    <button onclick="printForm()">Print Form</button>


</main>
<script>
    function printForm() {
        // Open a new window or popup with the form content
        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Your Form</title></head><body>');

        // Copy the content of the form section by its class
        printWindow.document.write(document.querySelector('.printform').innerHTML);

        printWindow.document.write('</body></html>');
        printWindow.document.close();

        // Trigger the print dialog
        printWindow.print();
    }
</script>
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

<script>
    $(function () {

// adjust the datatable
$("#example1").DataTable({
  "responsive": true, "lengthChange": false, "autoWidth": false,
  "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
  columnDefs: [
        { width: '5px', targets: [0] }, // Adjust the width of the first column (index 0)
        { width: '5px', targets: [1] }, // Adjust the width of the first column (index 0)
        { width: '10px', targets: [2] }
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