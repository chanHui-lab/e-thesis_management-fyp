<!-- resources/views/submission/details.blade.php -->
@extends('student.studentpure')

@section('master_content')
<main>
    <a class="btn btn-primary back-button" href="{{ route('stutemplate.index') }}">
        <i class="fas fa-arrow-left mr-2"></i> Back to Form Section
    </a>
    <h1 >{{ $submissionPost->title }}</h1>
    <p>{{ $submissionPost->description }} </p>

    <div class="card">
        <div class="card-body custom-gray-box">
            <p><strong>Opened:</strong> {{ $submissionPost->created_at }}</p>
            <p class="linee"><strong>Due:</strong> {{ $submissionPost->submission_deadline }}</p>
            @php
                $files = json_decode($submissionPost->files, true);
            @endphp
            <div class="arrow-box">

                @if (is_array($files) && count($files) > 0)
                @foreach ($files as $filee)
                &#x2514;

                <a href="{{ asset('storage/' . $filee['path']) }}" target="_blank" download class="downloadfile-link">
                    @if (Str::endsWith($filee['path'], '.pdf'))
                    <i class="fa fa-file-pdf file-icon" style = "color:  rgb(255, 86, 86)"></i>

                    @elseif (Str::endsWith($filee['path'], '.doc') || Str::endsWith($filee['path'], '.docx'))
                    <i class="fa fa-file-word file-icon" style = "color: rgb(77, 144, 250)"></i>

                    @else
                    <i class="fa fa-solid fa-file file-icon" style = "color: rgb(77, 144, 250)"></i>
                    @endif
                    {{ substr($filee['path'], strpos($filee['path'], '_') + 1) }}

                </a >

                {{-- <span style="font-size: 80%; margin-left: 5px;">
                    {{ \Carbon\Carbon::parse($filee['uploaded_at'])->format('Y-m-d h:i A') }}
                </span> --}}
                <br>@endforeach

                @endif
            </div>
        </div>
    </div>
    <h3 class="col-title">Form Submission Column</h3>
    @php
        $submissionDeadline = \Carbon\Carbon::parse($submissionPost->submission_deadline);
        $deadlineTimeDiff = $submissionDeadline->diff(now());

    @endphp
    @if ($formSubmission)

        <div class = "row row-buttonform" style="margin-left: 5px">
            <a href="{{ route('formSubmission.edit', ['formSubmissionId' => $formSubmission->id,'submissionPostId' => $submissionPost->id]) }}" class="btn btn-warning btn-common">
            {{-- <a href="{{ route('formSubmission.edit', ['formSubmissionId' => $formSubmission->id]) }}" class="btn btn-warning"> --}}

            {{-- <a class="btn btn-info btn-sm" href="{{ route('formpost.edit',$postform->id) }}"> --}}
                <i class="fas fa-pencil-alt">
                </i>
                Edit Submission
            </a>

            {{-- delete parteu --}}
            <form action="{{ route('formSubmission.delete',$formSubmission->id) }}" method="POST">

            <button type="button" class="btn btn-danger btn-common" data-toggle="modal" data-target="#deleteModal{{ $formSubmission->id }}">
                <i class="fas fa-trash"></i>
                Delete Submission
            </button>
            {{-- delet confirmation modal --}}
            <div class="modal fade" id="deleteModal{{ $formSubmission->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete the whole form submission?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                @csrf
                                @method('DELETE')
                            {{-- href="{{ route('formSubmission.delete', ['formSubmissionId' => $formSubmission->id]) }}" --}}
                            {{-- <a href="{{ route('formSubmission.delete', ['formSubmissionId' => $formSubmission->id]) }}" class="btn btn-danger">Delete</a> --}}
                            <button class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- @endforeach --}}
            </form>
            </div>
    @else
        {{-- @foreach($formSubmissions as $formSubmission) --}}
        <a href="{{ route('stuFormSubmission.create',['submission_post_id' =>  $submissionPost->id]) }}" class="btn btn-primary btn-common">
            <i class="fas fa-plus" style="margin-right: 5px"></i> Add Submission
        </a>


    @endif

    @if (session('successUpdate'))
        <div class="alert alert-success mt-4" >
            {{ session('successUpdate') }}
        </div>
    @endif

    @if (session('errorUpdate'))
        <div class="alert alert-danger mt-4">
            {{ session('errorUpdate') }}
        </div>
    @endif
    <div class = "card" style="margin-top: 16px">
    <table id="view1" class="custom-table generaltable table-bordered table table-striped" style="margin-left: 20px; margin-right: 20px; width:95%">

    {{-- @forelse($formSubmission as $formSubmissionee) --}}
    @if ($formSubmission)
    @php
        $formfiles = json_decode($formSubmission->form_files, true);
        $submissionStatus = (count($formfiles) > 0) ? "Submitted" : "Pending";

        $submissionDeadline = \Carbon\Carbon::parse($submissionPost->submission_deadline);
        $currentTime = now();
        $deadlineTimeDiff  = $submissionDeadline->diff($currentTime);

        $fileSubmissionTime = \Carbon\Carbon::parse($formSubmission->updated_at); // Assuming 'updated_at' is a timestamp
        $fileTimeDiff = $fileSubmissionTime->diff($submissionDeadline);
        // Check if files exist
        $filesExist = !empty($formfiles);
    @endphp


        <tbody>
            <tr>
                <th style="width:25%;"><strong>Submission Status</strong></th>
                {{-- <td style="margin:5px 10px; border-radius: 20px;" class="submission-status badge badge-success {{ $submissionStatus == 'Submitted' ? 'submitted' : 'pending' }}">{{ $submissionStatus }} --}}
                <td  class="submission-status">

                @if ($submissionStatus == 'Submitted')
                    <span class="status-visible">Submitted</span>
                @else
                    <span class="status-hidden">Pending</span>
                @endif

            </td>
            </tr>
            <tr>
                <th><strong>Form Title</strong></th>
                <td>{{ $formSubmission->form_title }}</td>
            </tr>
            <tr>
                <th>Description:</th>
                <td>{{ $formSubmission->description }}</td>
            </tr>
            <tr>
                <th>Author:</th>
                <td>{{ Auth::user()->name }} ({{ Auth::user()->student->matric_number }})</td>
            </tr>
            <tr>
                <th><strong>Time Remaining</strong></th>
                <td class="
                    @if ($filesExist)
                        @if ($fileTimeDiff->invert)
                            bg-light-red
                        @else
                            bg-light-green text-black
                        @endif
                    @else
                        @if ($deadlineTimeDiff->invert)
                            bg-light-red text-white
                        @else
                            bg-light-blue text-black
                        @endif
                    @endif
                ">
                    @if ($filesExist)
                        @if ($fileTimeDiff->invert)
                            <p>Assignment is submitted late by {{ $fileTimeDiff->format('%a days, %h hours, %i minutes') }}</p>
                        @else
                            <p>Submitted {{ $fileTimeDiff->format('%a days, %h hours, %i minutes') }} earlier</p>
                        @endif
                    @else
                        @if ($deadlineTimeDiff->invert)
                            <p class="text-red">Assignment is overdue by {{ $deadlineTimeDiff->format('%a days, %h hours, %i minutes') }}</p>
                        @elseif($submissionDeadline->isFuture())
                            <p>{{ $deadlineTimeDiff->format('%a days, %h hours, %i minutes') }} left</p>
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <th><strong>File Submission</strong></th>
                <td>
                    @if (is_array($formfiles) && count($formfiles) > 0)
                        @foreach ($formfiles as $file)
                        &#x2514;
                            <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" download class="downloadfile-link">
                                @if (Str::endsWith($file['path'], '.pdf'))
                                    <i class="fa fa-file-pdf file-icon" style="color: rgb(255, 86, 86)"></i>
                                @elseif (Str::endsWith($file['path'], '.doc') || Str::endsWith($file['path'], '.docx'))                                    <i class="fa fa-file-word file-icon" style="color: rgb(77, 144, 250)"></i>
                                    <i class="fa fa-file file-icon" style="color: rgb(77, 144, 250)"></i>
                                @else
                                    <i class="fa fa-file file-icon" style="color: rgb(77, 144, 250)"></i>
                                @endif
                                {{ pathinfo($file['path'])['filename'] }}

                                {{-- {{ substr($file['path'], strpos($file['path'], '/') + 1) }} --}}
                            </a>
                            <span style="font-size: 80%; margin-left: 5px;">
                                {{ \Carbon\Carbon::parse($file['uploaded_at'])->format('Y-m-d h:i A') }}
                            </span>
                            <br>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <th><strong>Submission Comment</strong></th>
                <td>
                    <div class="row">
                        <div class="col-md-12">
                          @if ($errors->any())
                          <div class="alert alert-danger">
                              <ul>
                                  @foreach ($errors->all() as $error)
                                      <li>{{ $error }}</li>
                                  @endforeach
                              </ul>
                          </div>
                          @endif

                          @if (session('success'))
                              <div class="alert alert-success">
                                  {{ session('success') }}
                              </div>
                          @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                      </div>
                    </div>

                    <ul id="comments-container">

                    <!-- DIRECT CHAT -->
                    <div class="card direct-chat direct-chat-primary">
                        <div class="card-tools" class="align-right" style="color:red; text-align: right;margin-right:10px;margin-top:10px;margin-bottom:10px;">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                            </button>
                            {{-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                            </button> --}}
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                        <!-- Conversations are loaded here -->
                        @foreach ($comments as $comment)

                        <div class="direct-chat-messages">
                            <!-- Message. Default to the left -->

                            <div class="direct-chat-msg ">
                                {{-- <li class="@if ($comment->student_id == Auth::id()) student-comment @else lecturer-comment @endif"> --}}
                                    <div class="direct-chat-infos clearfix">
                                    {{-- <span class="direct-chat-name float-left"><strong>{{ $comment->student->user->name }}</strong></span> --}}
                                        @if ($comment->student_id == Auth::id())
                                        <!-- Comment made by the student -->
                                        <strong>You (Student):</strong>
                                        @else
                                            <!-- Comment made by the lecturer -->
                                            <strong>{{ $comment->lecturer->user->name }} (Lecturer):</strong>
                                        @endif

                                    {{-- {{ $comment->comment_text }} --}}
                                    <span class="direct-chat-timestamp float-right" style="color: gray">{{ $comment->created_at->format('j M Y g:i a') }}</span>
                                </div>

                                <img class="direct-chat-img" style="border-radius: 50%;
                                float: left;
                                height: 40px;
                                width: 40px; vertical-align: middle;
                                border-style: none;"
                                src='{{ asset('./dist/img/user1-128x128.jpg') }}' alt="message user image">

                                <div class="direct-chat-text @if ($comment->student_id == Auth::id()) student-comment @else lecturer-comment @endif">
                                    {{ $comment->comment_text }}
                                    <!-- Add delete icon and link for the student's comment -->
                                    @if ($comment->student_id == Auth::id())
                                        <a href="{{ route('stuFormSubmission.deletecomment', ['commentId' => $comment->id]) }}" onclick="return confirm('Are you sure you want to delete this comment?')">
                                            <i class="fas fa-trash-alt" style="color:#444"></i>
                                        </a>
                                    @endif
                                </div>
                                {{-- </li> --}}
                                <br>
                            </div>

                            <!-- /.direct-chat-msg -->

                            {{-- <!-- Message to the right -->
                            <div class="direct-chat-msg right">
                            <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-right">Sarah Bullock</span>
                                <span class="direct-chat-timestamp float-left">23 Jan 6:10 pm</span>
                            </div>
                            <!-- /.direct-chat-infos -->
                            <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">
                            <!-- /.direct-chat-img -->
                            <div class="direct-chat-text">
                                I would love to.
                            </div>
                            <!-- /.direct-chat-text -->
                            </div>
                            <!-- /.direct-chat-msg --> --}}

                        </div>
                        <!--/.direct-chat-messages-->
                            @endforeach

                        {{-- </div> --}}
                        <!-- /.card-body -->
                        <div class="card-footer">
                        <form method="POST" action="{{ route('stuFormSubmission.addComment', ['formSubmissionId' => $formSubmission->id]) }}">
                            @csrf
                            <div class="input-group">
                            <input type="text" name="comment_text" placeholder="Type Message ..." class="form-control">
                            <span class="input-group-append">
                                <button type="submit" class="btn btn-primary black-btn">Send</button>
                            </span>
                            </div>
                        </form>
                        </div>
                        <!-- /.card-footer-->
                    </div>
                    <!--/.direct-chat -->
                    {{--<form method="POST" action="{{ route('stuFormSubmission.addComment', ['formSubmissionId' => $formSubmission->id]) }}">
                        @csrf
                        <textarea name="comment_text" rows="4"></textarea>
                        <button type="submit">Add Comment</button>
                    </form> --}}
                </td>
            </tr>
        </tbody>
    </table>
    </div>
    {{-- @empty --}}
    @else

        <tbody>
            <tr>
                <th style="width:25%;"><strong>Submission Status</strong></th>
                {{-- <td>Pending</td> --}}
               <td>
                @if ($submissionDeadline->isFuture())
                    {{-- <span class="v-chip-column chip--label bg-light-green text-black">Pending</span> --}}
                    <span class="status-hidden">Pending</span>

                @else
                    <span class="status-hidden">Overdue</span>
                    {{-- <span class=" v-chip-column chip--label bg-light-red text-white">Overdue</span> --}}
                @endif
               </td>
            </tr>
            <tr>
                <th><strong>Form Title</strong></th>
                <td>-</td>
            </tr>
            <tr>
                <th>Description:</th>
                <td>-</td>
            </tr>
            <tr>
                <th>Author:</th>
                <td>{{ Auth::user()->name }} ({{ Auth::user()->student->matric_number }})</td>
            </tr>
            <tr>
                <th><strong>Time Remaining</strong></th>
                {{-- <td class="
                    @if ($submissionDeadline->isFuture())
                        bg-light-green text-black
                    @else
                        bg-light-red text-white
                    @endif
                ">

                    @if ($submissionDeadline->isFuture())
                        <p class="text-white bg-light-red">Assignment is overdue by {{ $deadlineTimeDiff->format('%a days, %h hours, %i minutes') }}</p>
                    @elseif($deadlineTimeDiff)
                        <p>{{ $deadlineTimeDiff->format('%a days, %h hours, %i minutes') }} left</p>
                    @endif
                </td> --}}

                <td class="
                    @if ($submissionDeadline->isFuture())
                        bg-light-green text-black
                    @else
                        bg-light-red text-white
                    @endif
                ">
                    @if ($submissionDeadline->isFuture())
                        <p>{{ $deadlineTimeDiff->format('%a days, %h hours, %i minutes') }} left</p>
                    @else
                        <p class="text-white bg-light-red">Assignment is overdue by {{ $deadlineTimeDiff->format('%a days, %h hours, %i minutes') }}</p>
                    @endif
                </td>

            </tr>
            <tr>
                <th><strong>File Submission</strong></th>
                <td>-</td>
            </tr>
            <tr>
                <th><strong>Submission Comments</strong></th>
                <td>-</td>
            </tr>
        </tbody>
    </table>
{{-- @endforelse --}}
@endif

</main>

@endsection
