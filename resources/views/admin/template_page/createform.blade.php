@extends('admin.template_page.adminpure')

@section('master_content')

<main>
<h1 style = "padding-top: 20px; padding-bottom:20px">Create Form Template</h1>

 {{--handle error  --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There are some problem with input <br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- <div class = "containerr"> --}}
<!-- Create Post Form -->
{{-- <form method="post" class = "dropzone" id = "file_date" action="{{ url ('admin/adminpage/admintemplateupload')}}" enctype="multipart/form-data"> --}}
  {{-- <form method="post" action="{{ url ('admin/adminpage/admintemplateupload')}}" enctype="multipart/form-data"> --}}
  <form method="post" action="{{ route('template.store') }}" enctype="multipart/form-data">

    @csrf

    <div class="namestyle">
        <label for="file_name"  style = "margin-top: 10px;">Template Name:</label>
        <input type="text" id="file_name" name="file_name" class = "form-control" placeholder="Template Name" ><br>
    </div>

    <label for="description">Description:</label>
    <textarea id="description" class = "form-control" style="height: 150px" name="description" rows="4" cols="50" placeholder="Details" ></textarea><br>

  <label for="file_data">Upload File:</label>
  {{-- <div class="dropzone" id="file_data" name="file_data"> --}}
    <div id = "dropzone" class="row ">
      <div class="col-md-12">
        <div class="card card-default">

          <div class="card-body">
            <div id="actions" class="row">
              <div class="col-lg-6">
                <div class="btn-group w-100">
                  <span class="btn btn-success col fileinput-button">
                    <i class="fas fa-plus"></i>
                    <span>Add files</span>
                  </span>
                  <button type="submit" class="btn btn-primary col start filesubmit-button">
                    <i class="fas fa-upload"></i>
                    <span>Start upload</span>
                  </button>
                  <button type="reset" class="btn btn-warning col cancel filecancel-button">
                    <i class="fas fa-times-circle"></i>
                    <span>Cancel upload</span>
                  </button>
                </div>
              </div>
              <div class="col-lg-6 d-flex align-items-center">
                <div class="fileupload-process w-100">
                  <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                    <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                  </div>
                </div>
              </div>
            </div>


            <div class="table table-striped files" id="previews">
              <div id="template" class="row mt-2">
                <div class="col-auto">
                    <span class="preview"><img src="data:," alt="" data-dz-thumbnail /></span>
                </div>
                <div class="col d-flex align-items-center">
                    <p class="mb-0">
                      <span class="lead" data-dz-name></span>
                      (<span data-dz-size></span>)
                    </p>
                    <strong class="error text-danger" data-dz-errormessage></strong>
                </div>
                <div class="col-4 d-flex align-items-center">
                    <div class="progress progress-striped active w-100" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                      <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                    </div>
                </div>
                <div class="col-auto d-flex align-items-center">
                  <div class="btn-group">
                    <button class="btn btn-primary start">
                      <i class="fas fa-upload"></i>
                      <span>Start</span>
                    </button>
                    <button data-dz-remove class="btn btn-warning cancel">
                      <i class="fas fa-times-circle"></i>
                      <span>Cancel</span>
                    </button>
                    <button data-dz-remove class="btn btn-danger delete">
                      <i class="fas fa-trash"></i>
                      <span>Delete</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card-body -->
        <!-- /.card -->
      </div>
    </div>
  </div>
    <label for="status">Statusss:</label>
    <div class = "custom-select">
    <select class="form-control" id="status" name="status">
        <option value = "0"> Hidden </option>
        <option value = "1"> Visible </option>
    </select>
    </div>

    <input class="btn btn-primary" type="submit">  </div>



</form>
</div>

@endsection

@section('scripts')

<script>
    flatpickr(".datetimepicker", {
        enableTime: true,
        dateFormat: "Y-m-d H:i", // Change this format according to your needs
    });
</script>

<style>
/* Style the select element */
.card .card-body .custom-select {
    /* display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    background-color: #e0ebb9;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; */
}

/* Style the select element when it's in focus */
.custom-select:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Style the dropdown arrow */
.custom-select::after {
    content: "\25BC"; /* Unicode character for a down arrow */
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
}

/* Style the dropdown options */
.custom-select option {
    background-color: #fff;
    color: #333;
}

/* Style the selected option */
.custom-select option:checked {
    background-color: #007bff;
    color: #fff;
}
</style>

@endsection





