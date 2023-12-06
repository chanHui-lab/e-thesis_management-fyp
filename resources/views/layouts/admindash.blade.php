@php
use Illuminate\Support\Facades\Auth;
@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <!-- Add this line to include Lato font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700&display=swap" rel="stylesheet">

    <!-- Add this line to include Raleway font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}

    <!-- jQuery (required by Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 4 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>

    {{-- CSS bootrap --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> --}}

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.9.55/css/materialdesignicons.min.css">


    <!-- My CSS -->
	<link rel="stylesheet" href="{{ asset('admindash/admindash.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('./dist/css/adminlte.min.css') }}"> --}}

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>e-thesis management system</title>

    {{-- @livewireStyles --}}
</head>
<body>
    <div class = "container-scroller">
        @include('admin.admindashinside.navbar')
        <!-- Orange Section -->
        <div class="orange-section">
            <!-- Your image goes here -->
            {{-- <img src='{{ asset('admindash/img/PMbg.png') }}' alt="Your Image"> --}}

        <!-- Grey Section -->
        <div class="grey-section">
            <!-- CONTENT -->
            {{-- <img src='{{ asset('admindash/img/PMbg.png') }}' alt="Your Image"> --}}

            <div class="container-fluid page-body-wrapper">
                @if (Auth::user()->role_as == 0)
                    @include('admin.admindashinside.sidebar')
                @elseif (Auth::user()->role_as == 2)
                    @include('student.studentinside.stusidebar')
                @endif

                <div class = "content-wrapper">
                    @yield('content')
                </div>
                </div>
            </div>
        </div>

    </div>


    <script src= "{{ asset('admindash/admindashscript.js') }}"></script>

    <script src= "{{ asset('admindash/adminhighlight.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('./dist/js/pages/dashboard.js') }}"></script>
    <script src="{{ asset('./dist/js/adminlte.min.js') }}"></script>

    @livewireScripts
</body>
<style>
    /* .container{
        font-family: 'Poppins', sans-serif;
    } */

    body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            /* background: linear-gradient(#f0cc55 40%, #f5f5f7 25%); */
            background: linear-gradient(#FFE681 40%, #f5f5f7 25%);

            /* background-size: 100% 25%; Adjust the size based on your needs */
            background-repeat: no-repeat;
            background-position: top;
        }
    /* e0e0e0 */
    .container-scroller {
            position: relative;
            z-index: 1;
        }
    .orange-section {
        /* position: absolute; */
        top: 0;
        /* left: 280px; */
        /* width: 40%; */
        height: 30%;
        /* background: url('{{ asset('admindash/img/PMbg.png') }}') repeat; Use cover to make the image cover the entire section */
        /* background-size: 20% 30%; Adjust the size based on your needs */
        /* background: linear-gradient(orange, orange) 0 0%, url('{{ asset('admindash/img/PMbg.png') }}') repeat; */
        /* background: url('{{ asset('admindash/img/PMbg.png') }}') repeat; */

/* use thiss */
        /* background: url('{{ asset('admindash/img/PM.png') }}') center / cover no-repeat;; */

        /* background-position: top, center; */
        /* opacity: 0.5; Adjust the opacity value (0 to 1) as needed */

        z-index: -1;
    }

    .grey-section {
        /* Additional styles for the grey section if needed */
        z-index: 0;

    }

    .orange-section img {
        /* Styles for the image in the orange section */
        max-width: 100%;
        height: auto;
    }
    </style>

</html>