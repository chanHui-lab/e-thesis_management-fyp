<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Putramas E-thesis Management System</title>


    {{-- FOR TOAST NOTI --}}
    <!-- jQuery -->
    {{-- <script src="../../plugins/jquery/jquery.min.js"></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    {{-- <script src="{{ mix('/js/app.js') }}"></script>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}"> --}}
    <link rel="stylesheet" href="{{ url('./plugins/fontawesome-free/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ url('./plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('./plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('./plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- fullCalendar -->
    <link rel="stylesheet"  href="{{ url('./plugins/fullcalendar/main.css') }}">

    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,500,700&display=swap">
    <link rel="stylesheet" href="{{ url('/plugins/fontawesome-free/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ url('./plugins/dropzone/min/dropzone.min.css') }}">
    <script src="{{ asset('./plugins/dropzone/min/dropzone.min.js') }}"></script>

    {{-- css style --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('admindash/stuform.css') }}">
    {{-- <link rel="stylesheet" href="{{ url('./admindash/stuform.css') }}"> --}}
    <link rel="stylesheet" href="{{ url('./admindash/adminform.css') }}">
    <link rel="stylesheet" href="{{ url('./admindash/admincalendar.css') }}">
    <link rel="stylesheet" href="{{ url('./admindash/studash.css') }}">

    <script src="{{ asset('./admindash/adminform.js') }}"></script>

    {{-- FOR VUE --}}
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">

    {{-- <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script> --}}
    {{-- <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" /> --}}

    <!-- Theme style -->
    {{-- <link rel="stylesheet" href="{{ url('./dist/css/adminlte.min.css') }}"> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.css" />

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ url('./plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

    <!-- Toastr -->
    <link rel="stylesheet" href="{{ url('./plugins/toastr/toastr.min.css') }}">

    <!-- SweetAlert2 -->
    <script src="{{ asset('./plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('./plugins/toastr/toastr.min.js') }}"></script>

</head>
<body>
    @extends('layouts.admindash')
    @section('content')

    <div class="container-fluid page-body-wrapper">
        <div class = "main-panel">
            <div class = "content-wrapper">
                    @yield('master_content')
                </main>
            </div>
        </div>
    </div>
    @endsection

</body>
<style>

/* body {
    margin: 0;
    padding: 0;
    height: 100vh;
    display: flex;
    flex-direction: column;
    background: linear-gradient(orange 25%, #e0e0e0 25%);
} */

.fc-next-button {
  background-color: #FACD3F;
}

/* Previous button */
.fc-prev-button {
  background-color: #FACD3F;
}

/* Today button */
.fc-today-button {
  background-color: #FACD3F;
}
    </style>

</html>