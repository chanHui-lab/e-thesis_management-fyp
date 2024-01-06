@extends('admin.template_page.adminpure')

@section('master_content')

<main>


    <div id="app">

        <component-a></component-a>
    </div>

</main>
@endsection

<script type="module" src="{{ mix('resources/js/app.js') }}">
