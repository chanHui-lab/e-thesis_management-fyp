@extends('student.studentpure')

@section('master_content')
{{-- TO CREATE  --}}
<main>
    <h1 style = "padding-top: 20px; padding-bottom:5px">Presentation Slide submission</h1>
    <h4 style = "padding-bottom:5px; color:gray;">Submitted by: {{ Auth::user()->name }}</h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

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

    <a href="{{ route('stuSlideSubmission.details', ['submissionPostId' => $submissionPostId]) }}" style="margin-bottom: 20px" class="btn btn-primary">Back</a>

    <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Upload Presentation Slide</h3>
        </div>

        <form method="post" action="{{ route('stuSlideSubmission.store')}}" enctype="multipart/form-data">

            @csrf
            <input type="hidden" name="submission_post_id" value="{{ $submissionPostId }}">

            <div class="card-body">
            <div class="form-group">
                <label for="slide_title"  class = "labelling">Title:</label>
                <input type="text" id="slide_title" name="slide_title" class = "form-control" placeholder="Enter Title..." ><br>
            </div>
            <div class="form-group">
                <label for="slide_description" class = "labelling">Description:</label>
                <textarea id="slide_description" class = "form-control" style="height: 150px" name="slide_description" rows="4" cols="50" placeholder="Details" ></textarea><br>
            </div>

            <label for="slide_file" class = "labelling">Upload Presentation Slide Files:</label><br>
            <div class="wrapper mb-5">

                <div class="container">
                <div class="row">
                    <div class="col-md-12 pl-0">
                        {{-- <h4>File Upload</h4> --}}
                        <div class="input-group hdtuto control-group increment">
                            <input type="file" name="slide_file[]" class="myfrm form-control">
                            <div class="input-group-btn">
                                <button class="btn btn-success" type="button" onclick="addFileInput(this)">
                                    <i class="fldemo glyphicon glyphicon-plus"></i>Add
                                </button>
                            </div>
                        </div>
                        <div class="clone hide">
                            <div class="hdtuto control-group lst input-group ml-0" style="margin-top:10px">
                                <input type="file" name="slide_file[]" class="myfrm form-control">
                                <div class="input-group-btn">
                                    <button class="btn btn-danger" type="button" onclick="removeFileInput(this)">
                                        <i class="fldemo glyphicon glyphicon-remove"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


          </div>
          <!-- /.card-body -->

          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    flatpickr(".datetimepicker", {
        enableTime: true,
        dateFormat: "Y-m-d H:i", // Change this format according to your needs
    });

    function addFileInput(element) {
      var clone = $(element).closest('.increment').clone();
      clone.find('input').val('');
      clone.insertAfter($(element).closest('.increment'));
      clone.find('.btn-success').html('<i class="fldemo glyphicon glyphicon-remove"></i> Remove').removeClass('btn-success').addClass('btn-danger').attr('onclick', 'removeFileInput(this)');
      clone.css('margin-top', '10px'); // Add margin to the new row
    }
    function removeFileInput(button) {
          // const parent = button.closest('.increment');
          // parent.remove();
          $(button).closest('.increment').remove();
      }

</script>
@endsection