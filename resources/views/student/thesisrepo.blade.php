@extends('student.studentpure')

@section('master_content')

<main>

    <div id="app">
        {{-- <router-view :server-data="{{ json_encode($data) }}"></router-view> --}}
        <component-a></component-a>
    </div>

</main>
@endsection

<script type="module" src="{{ mix('resources/js/app.js') }}">
