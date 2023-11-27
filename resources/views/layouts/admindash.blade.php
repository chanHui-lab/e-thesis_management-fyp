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

        <!-- CONTENT -->
 	<div class="container-fluid page-body-wrapper">
        @if (Auth::user()->role_as == 0)
            @include('admin.admindashinside.sidebar')
        @elseif (Auth::user()->role_as == 2)
            @include('student.studentinside.stusidebar')
        @endif

		<div class = "main-panel">
			<div class = "content-wrapper">
				@yield('content')
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
    .container{
        font-family: 'Poppins', sans-serif;
    }
    </style>

</html>