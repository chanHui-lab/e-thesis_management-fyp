@extends('admin.template_page.adminpure')

@section('master_content')

<div class="submission-tab">
    {{-- <div class="tab-button"> --}}
        <a class="btn btn-primary back-button" href="{{ route('thesispost.showAll', ['submissionPostId' => $submissionPostId]) }}">Back</a>

    <div class="tab-content">
        <p> Submitted by:  <strong>{{ $thesisSubmission->student->matric_number }} {{ $thesisSubmission->student->user->name }}</strong></p>
    </div>
</div>

    <h1 style="margin-top: 20px">{{ $thesisSubmission->submissionPost->title }}</h1>

    <p>{{ $thesisSubmission->submissionPost->description }} </p>

    <div class="card">

        <div class="card-body custom-gray-box">
            <p><strong>Opened:</strong> {{ $thesisSubmission->submissionPost->created_at }}</p>
            <p class="linee"><strong>Due:</strong> {{ $thesisSubmission->submissionPost->submission_deadline }}</p>
            @php
                $files = json_decode($thesisSubmission->submissionPost->files, true);
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
                <span style="font-size: 80%; margin-left: 5px;">
                    {{ \Carbon\Carbon::parse($filee['uploaded_at'])->format('Y-m-d h:i A') }}
                </span>
                <br>
                        @endforeach
                @endif

        </div>
        </div>
    </div>

    @php
        $formfiles = json_decode($thesisSubmission->thesis_file, true);
        $submissionStatus = (count($formfiles) > 0) ? "Submitted" : "Pending";

        $submissionDeadline = \Carbon\Carbon::parse($thesisSubmission->submissionPost->submission_deadline);
        $currentTime = now();
        $deadlineTimeDiff  = $submissionDeadline->diff($currentTime);

        $fileSubmissionTime = \Carbon\Carbon::parse($thesisSubmission->updated_at); // Assuming 'updated_at' is a timestamp
        $fileTimeDiff = $fileSubmissionTime->diff($submissionDeadline);
        // Check if files exist
        $filesExist = !empty($formfiles);

    @endphp

    <h2 style="margin-top: 20px">Thesis Submission</h2>


    <div class = "card" style="margin-top: 16px">

    <table id="view1" class="custom-table generaltable table-bordered table table-striped" style="margin-left: 20px; margin-right: 20px; width:95%">
        <tbody>
                <tr>
                    <th style="width:25%;"><strong>Submission Status</strong></th>
                    {{-- <td class="submission-status badge badge-success {{ $submissionStatus == 'Submitted' ? 'submitted' : 'pending' }}">
                        {{ $submissionStatus }} --}}
                    <td style="margin:5px 10px; " class="submission-status badge badge-success {{ $submissionStatus == 'Submitted' ? 'submitted' : 'pending' }}">{{ $submissionStatus }}</td>

                    {{-- </td> --}}

                    {{-- <td class="submission-status {{ $submissionStatus == 'Submitted' ? 'submitted' : 'pending' }}"> {{ $submissionStatus }}</td> --}}
                </tr>
                <tr>
                    <th ><strong>Thesis Title</strong></th>
                    <td> {{ $thesisSubmission->thesis_title }}

                    @if ($thesisSubmission->thesis_status === 'approved')
                        <span class="badge badge-success" style="font-size: 1em; margin-left:10px;"> Approved as Thesis Reference</span>
                    @elseif ($thesisSubmission->thesis_status  === 'pending')
                        <span class="badge badge-warning text-dark" style="font-size: 1em; margin-left:10px;"> Pending</span>
                    @elseif ($thesisSubmission->thesis_status  === 'rejected')
                        <span class="badge badge-danger" style="font-size: 2em; margin-left:10px;"> Rejected </span>
                    @endif
                </td>
                </tr>
                <tr>
                    <th>Abstract:</th>
                    <td>{{ $thesisSubmission->thesis_abstract }}</td>
                </tr>
                <tr>
                    <!-- Calculate time remaining -->
                    {{-- @php
                        $submissionDeadline = \Carbon\Carbon::parse($formSubmission->submissionPost->submission_deadline);
                        $currentTime = now();
                        $timeRemaining = $submissionDeadline->isPast() ? 'Submission closed' : $submissionDeadline->diffForHumans($currentTime);
                    @endphp --}}

                    {{-- display 1 week... --}}
                    {{-- @php
                        $submissionDeadline = \Carbon\Carbon::parse($formSubmission->submissionPost->submission_deadline);
                        $currentTime = now();
                        if ($submissionDeadline->isFuture()) {
                            // Deadline is in the future (time remaining)
                            $timeRemaining = $submissionDeadline->diffForHumans($currentTime);
                        } else {
                            // Deadline has passed (time passed)
                            $timePassed = $submissionDeadline->diffForHumans($currentTime);
                        }
                    @endphp --}}

                    <th><strong>Time Remaining</strong></th>
                    {{-- <td> {{ $formSubmission->form_title }}</td> --}}
                    <td class="@if ($filesExist)
                            @if ($fileTimeDiff->invert)
                                bg-light-red
                            @else
                                bg-light-green text-black
                            @endif
                        @else
                            @if ($deadlineTimeDiff)
                            bg-red text-white
                            @elseif ($submissionDeadline->isFuture())
                            bg-light-blue text-black
                            @endif
                            @endif">
                    {{-- @if (isset($timeRemaining))
                        {{ $timeRemaining }}
                    @else
                       {{ $timePassed }}
                    @endif --}}

                    {{-- @if ($formSubmission->form_files && !$currentTime->gt($submissionDeadline))
                            <p>Time Remaining: {{ $diff->format('%a days, %h hours, %i minutes') }}</p>
                        @elseif ($formSubmission->form_files && $currentTime->gt($submissionDeadline))
                            <p>Submission is late by {{ $diff->format('%a days, %h hours, %i minutes') }}</p>
                        @elseif (!$formSubmission->form_files && !$currentTime->gt($submissionDeadline))
                            <p>Time Remaining: {{ $diff->format('%a days, %h hours, %i minutes') }}</p>
                        @elseif (!$formSubmission->form_files && $currentTime->gt($submissionDeadline))
                            <p>Assignment is overdue by {{ $diff->format('%a days, %h hours, %i minutes') }}</p>
                        @endif --}}
                    {{-- @if ($formSubmission->form_files && $uploadDiff->invert == 0)  <!-- Check if the uploaded date is before the deadline -->
                        <p>Time Remaining: {{ $diff->format('%a days, %h hours, %i minutes') }}</p>
                    @elseif ($formSubmission->form_files && $uploadDiff->invert == 1)  <!-- Check if the uploaded date is after the deadline -->
                        <p>Submission is late by: {{ $uploadDiff->format('%a days, %h hours, %i minutes') }}</p>
                    @elseif (!$formSubmission->form_files && $diff->invert == 0)  <!-- Check if the current time is before the deadline -->
                        <p>Time Remaining: {{ $diff->format('%a days, %h hours, %i minutes') }}</p>
                    @else
                        <p>Assignment is overdue by {{ $diff->format('%a days, %h hours, %i minutes') }}</p>
                    @endif --}}

                    {{-- {{ $formSubmission->getTimeRemainingOrElapsed() }} --}}
                    {{-- @if ($submissionDeadline->isFuture())
                        @if ($filesExist && $lastFileUploadTime < $submissionDeadline)
                            <p>Submitted {{ $fileTimeDiff->format('%a days, %h hours, %i minutes') }} earlier</p>
                        @elseif ($filesExist)
                            <p>Submission has been late by {{ $fileTimeDiff->format('%a days, %h hours, %i minutes') }}</p>
                        @else
                            <p>{{ $diff->format('%a days, %h hours, %i minutes') }} left</p>
                        @endif
                    @else
                        <p>Assignment is overdue by {{ $diff->format('%a days, %h hours, %i minutes') }}</p>
                    @endif --}}
                    @if ($filesExist)

                        @if ($fileTimeDiff->invert)
                                <p>Assignment is submitted late by {{ $fileTimeDiff->format('%a days, %h hours, %i minutes') }}</p>
                        @else
                                <p>Submitted {{ $fileTimeDiff->format('%a days, %h hours, %i minutes') }} earlier</p>
                        @endif
                    @else
                        @if ($deadlineTimeDiff)
                            <p class="text-red">Assignment is overdue by {{ $deadlineTimeDiff->format('%a days, %h hours, %i minutes') }}</p>
                        @elseif($submissionDeadline->isFuture())
                            <p>{{ $deadlineTimeDiff->format('%a days, %h hours, %i minutes') }} left</p>
                        @endif
                    @endif

                    </td>

                </tr>
                @if (is_array($formfiles) && count($formfiles) > 0)

                    <tr>
                        <th><strong>File Submission</strong></th>
                        <td>
                            @foreach ($formfiles as $file)
                            &#x2514;
                            <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" download class="downloadfile-link">
                                @if (Str::endsWith($file['path'], '.pdf'))
                                    <i class="fa fa-file-pdf file-icon" style="color: rgb(255, 86, 86)"></i>
                                @elseif (Str::endsWith($file['path'], '.doc') || Str::endsWith($file['path'], '.docx'))
                                    <i class="fa fa-file-word file-icon" style="color: rgb(77, 144, 250)"></i>
                                @else
                                    <i class="fa fa-file file-icon" style="color: rgb(77, 144, 250)"></i>
                                @endif
                                {{ pathinfo($file['path'])['filename'] }}
                            </a>
                            <span style="font-size: 80%; margin-left: 5px;">
                                {{ \Carbon\Carbon::parse($file['uploaded_at'])->format('Y-m-d h:i A') }}
                            </span>
                            <br>
                            @endforeach
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th><strong>Submission Comment</strong></th>
                        {{-- <td> {{ $formSubmission->form_title }}</td> --}}
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
                                                @if ($comment->lecturer_id == Auth::id())
                                                <!-- Comment made by the student -->
                                                <strong>You (Lect):</strong>
                                                @else
                                                    <!-- Comment made by the lecturer -->
                                                    <strong>{{ $comment->student->user->name }} (Student):</strong>
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

                                        <div class="direct-chat-text @if ($comment->lecturer_id == Auth::id()) student-comment @else lecturer-comment  @endif">
                                            {{ $comment->comment_text }}
                                            <!-- Add delete icon and link for the lecturer's comment -->
                                            @if ($comment->lecturer_id == Auth::id())
                                                <a href="{{ route('lectFormSubmission.deletecomment', ['commentId' => $comment->id]) }}" onclick="return confirm('Are you sure you want to delete this comment?')">
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
                                <form method="POST" action="{{ route('formpostShow.addComment', ['formSubmissionId' => $thesisSubmission->id]) }}">
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
            {{-- @endforeach
            @endif --}}

        </table>
</div>

    {{-- <h3 style = "margin-top: 20px">Submission Details</h3>
<p>Form Title: {{ $formSubmission->form_title }}</p>
<p>Description: {{ $formSubmission->description }}</p>
<p>Form Files:</p>
<ul>
    @foreach (json_decode($formSubmission->form_files) as $file)
        <li>Path: {{ $file->path }}</li>
        <li>Uploaded At: {{ $file->uploaded_at }}</li>
    @endforeach
</ul> --}}
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

@endsection
