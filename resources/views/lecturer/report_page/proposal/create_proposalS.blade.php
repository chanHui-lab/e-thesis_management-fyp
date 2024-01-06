@extends('admin.template_page.adminpure')

@section('master_content')

{{-- TO CREATE  --}}
<main>
    <h1 style = "padding-top: 20px; padding-bottom:20px">Create Proposal Submission Post</h1>

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

    <a href="{{ route('lectproposalpost.index') }}" style = "margin-bottom:20px" class="btn btn-primary">Back</a>

    <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Create</h3>
        </div>

        <form method="post" action="{{ route('lectformpost.store')}}" enctype="multipart/form-data">

            @csrf
            <div class="card-body">
            <div class="form-group">
                <label for="title"  class = "labelling">Title:</label>
                <input type="text" id="title" name="title" class = "form-control" placeholder="Enter Title..." ><br>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" class = "form-control" style="height: 150px" name="description" rows="4" cols="50" placeholder="Details" ></textarea><br>
            </div>
            <div class="form-group">
                <label for="submission_deadline">Submission Deadline:</label>
                <input type="datetime-local" id="submission_deadline" name="submission_deadline" class="form-control datetimepicker" placeholder="Submission Deadline" >
            </div>

              {{-- <div class="form-group"> --}}
                  <label for="visibility_status">Status:</label>
                  <select class="custom-select" id="visibility_status" name="visibility_status" style= "display: block;
                  width: 100%;
                  padding: 0.375rem 0.75rem;
                  font-size: 1rem;
                  line-height: 1.5;
                  background-color: #fff;
                  background-clip: padding-box;
                  border: 1px solid #ced4da;
                  border-radius: 0.25rem;
                  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;" id="status" name="status" >
                      <option value = "0"> Hidden </option>
                      <option value = "1"> Visible </option>
                  </select>

              <br>

             <label for="files">Upload Document Files: (not necessary) </label><br>
                    <div class="wrapper">
                      {{-- <div class ="dotted">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Browse File</p>
                      </div> --}}
                      {{-- <input type="file" name="files[]" id="files" multiple>
                      <ul id="selected-files"></ul> <!-- This is where we'll display the selected file names --> --}}

                      {{-- <div class="input-group hdtuto control-group lst increment" >
                        <input type="file" name="filenames[]" class="myfrm form-control">
                        <div class="input-group-btn">
                          <button class="btn btn-success" type="button"><i class="fldemo glyphicon glyphicon-plus"></i>Add</button>
                        </div>
                      </div>
                      <div class="clone hide">
                        <div class="hdtuto control-group lst input-group" style="margin-top:10px">
                          <input type="file" name="filenames[]" class="myfrm form-control">
                          <div class="input-group-btn">
                            <button class="btn btn-danger" type="button"><i class="fldemo glyphicon glyphicon-remove"></i> Remove</button>
                          </div>
                        </div>
                      </div> --}}


                      {{-- <div class="input-group hdtuto control-group lst increment" style="margin-top:10px">
                        <input type="file" name="files[]" class="myfrm form-control">
                        <div class="input-group-btn">
                            <button class="btn btn-success" type="button" onclick="addFileInput(this)"><i class="fldemo glyphicon glyphicon-plus"></i>Add</button>
                        </div>
                      </div>
                    <div class="clone hide">
                        <div class="hdtuto control-group lst input-group" style="margin-top:10px">
                            <input type="file" name="files[]" class="myfrm form-control">
                            <div class="input-group-btn">
                                <button class="btn btn-danger" type="button" onclick="removeFileInput(this)"><i class="fldemo glyphicon glyphicon-remove"></i> Remove</button>
                            </div>
                        </div>
                      </div> --}}

                      <div class="container">
                        <div class="row">
                            <div class="col-md-12 pl-0">
                                {{-- <h4>File Upload</h4> --}}
                                <div class="input-group hdtuto control-group increment">
                                    <input type="file" name="files[]" class="myfrm form-control">
                                    <div class="input-group-btn">
                                        <button class="btn btn-success" type="button" onclick="addFileInput(this)">
                                            <i class="fldemo glyphicon glyphicon-plus"></i>Add
                                        </button>
                                    </div>
                                </div>
                                <div class="clone hide">
                                    <div class="hdtuto control-group lst input-group" style="margin-top:10px">
                                        <input type="file" name="files[]" class="myfrm form-control">
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
            <br>
          </div>
          <!-- /.card-body -->

          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>


    {{-- @section('scripts') --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}
    {{-- <script src="../../plugins/jquery/jquery.min.js"></script> --}}

    <script>
        flatpickr(".datetimepicker", {
            enableTime: true,
            dateFormat: "Y-m-d H:i", // Change this format according to your needs
        });

        // $(document).ready(function() {
        //     $('#files').on('change', function() {
        //         var fileNames = [];
        //         var files = $(this)[0].files;
        //         for (var i = 0; i < files.length; i++) {
        //             fileNames.push(files[i].name);
        //         }
        //         // Display the file names in an unordered list
        //         $('#selected-files').empty(); // Clear previous selections
        //         fileNames.forEach(function(fileName) {
        //             $('#selected-files').append('<li>' + fileName + '</li>');
        //         });
        //     });
        // });


        // document.getElementById('files').addEventListener('change', function () {
        //     const filesInput = document.getElementById('files');
        //     const selectedFilesList = document.getElementById('selected-files');

        //     // Clear the list before adding new files
        //     selectedFilesList.innerHTML = '';

        //     // Create an array to store the selected file names
        //     const fileNames = [];

        //     for (let i = 0; i < filesInput.files.length; i++) {
        //         fileNames.push(filesInput.files[i].name);
        //     }

        //     // Update the list with all the selected files
        //     fileNames.forEach(function (fileName) {
        //         const li = document.createElement('li');
        //         li.textContent = fileName;
        //         selectedFilesList.appendChild(li);
        //     });
        // });


    //     const input = document.getElementById('files');
    // const list = document.getElementById('selected-files');

    // input.addEventListener('change', () => {
    //     list.innerHTML = '';

    //     for (const file of input.files) {
    //         const item = document.createElement('li');
    //         item.textContent = file.name;
    //         list.appendChild(item);
    //     }
    // });


    // $(document).ready(function() {
    //   $(".btn-success").click(function(){
    //       var lsthmtl = $(".clone").html();
    //       $(".increment").after(lsthmtl);
    //   });
    //   $("body").on("click",".btn-danger",function(){
    //       $(this).parents(".hdtuto").remove();
    //   });
    // });

      // function addFileInput(button) {
      //     const clone = button.closest('.increment').cloneNode(true);
      //     document.querySelector('form').appendChild(clone);
      //     clone.classList.remove('hide');
      // }

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
