@extends('student.studentpure')

@section('master_content')
{{-- TO CREATE  --}}
<main>
    <h1 style = "padding-top: 20px; padding-bottom:5px">Proposal submission</h1>
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

    <a href="{{ route('stuProposalSubmission.details', ['submissionPostId' => $submissionPostId]) }}" style="margin-bottom: 20px" class="btn btn-primary">Back</a>

    <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Upload Proposal</h3>
        </div>

        <form method="post" action="{{ route('stuProposalSubmission.store')}}" enctype="multipart/form-data">

            @csrf
            <input type="hidden" name="submission_post_id" value="{{ $submissionPostId }}">

            <div class="card-body">
            <div class="form-group">
                <label for="proposal_title"  class = "labelling">Title:</label>
                <input type="text" id="proposal_title" name="proposal_title" class = "form-control" placeholder="Enter Title..." ><br>
            </div>
            <div class="form-group">
                <label for="proposal_description" class = "labelling">Description:</label>
                <textarea id="proposal_description" class = "form-control" style="height: 150px" name="proposal_description" rows="4" cols="50" placeholder="Details" ></textarea><br>
            </div>

            <label for="proposal_file" class = "labelling">Upload Proposal Files:</label><br>
            <div class="wrapper">

                <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        {{-- <h4>File Upload</h4> --}}
                        <div class="input-group hdtuto control-group increment">
                            <input type="file" name="proposal_file[]" class="myfrm form-control">
                            <div class="input-group-btn">
                                <button class="btn btn-success" type="button" onclick="addFileInput(this)">
                                    <i class="fldemo glyphicon glyphicon-plus"></i>Add
                                </button>
                            </div>
                        </div>
                        <div class="clone hide">
                            <div class="hdtuto control-group lst input-group" style="margin-top:10px">
                                <input type="file" name="proposal_file[]" class="myfrm form-control">
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

             <br>
             <div class="form-group">
                <label>Type of Thesis:</label>
                <div class="custom-checkbox-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="web_development" name="proposal_type[]" value="web_development">
                        <label class="custom-control-label" for="web_development">Website Development</label>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="mobile_development" name="proposal_type[]" value="mobile_development">
                        <label class="custom-control-label" for="mobile_development">Mobile App Development</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="machine_learning" name="proposal_type[]" value="machine_learning">
                        <label class="custom-control-label" for="machine_learning">Machine Learning</label>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="data_analytics" name="proposal_type[]" value="data_analytics">
                        <label class="custom-control-label" for="data_analytics">Data Analytics</label>
                    </div>
                    <!-- Add more checkboxes as needed -->
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