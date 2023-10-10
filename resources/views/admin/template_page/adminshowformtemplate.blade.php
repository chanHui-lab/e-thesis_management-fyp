{{-- just a showing template that is temporarily unused --}}
@extends('admin.template_page.adminpure')

@section('master_content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Show Product</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('template.index') }}"> Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group my-custom-form-group">
                <strong class="my-custom-strong">Name:</strong>
                {{ $template->file_name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group my-custom-form-group">
                <strong class="my-custom-strong">Description for students:</strong>
                {{ $template->description }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group my-custom-form-group">
                <strong class="my-custom-strong">Status:</strong>
                {{ $template->status }} from the students
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group my-custom-form-group">
                <strong class="my-custom-strong">PDF Preview:</strong>
                <br>
                <iframe src="{{ $pdfUrl }}" width="100%" height="600px"></iframe>
            </div>
        </div>
    </div>

@endsection