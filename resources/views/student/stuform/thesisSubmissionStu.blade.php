@extends('student.studentpure')
@section('master_content')

<main>

    <a class="btn btn-primary back-button" href="{{ route('stuThesistemplate.index') }}">
        <i class="fas fa-arrow-left mr-2"></i> Back to Thesis Section
    </a>

    <h1>{{ $submissionPost->title }}</h1>
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

    <h3 class="col-title">Thesis Submission Column</h3>
    @php
        $submissionDeadline = \Carbon\Carbon::parse($submissionPost->submission_deadline);
        $deadlineTimeDiff = $submissionDeadline->diff(now());

    @endphp
    @if ($thesisSubmission)
    <div class = "row row-buttonform" style="margin-left: 5px">
        <a href="{{ route('thesisSubmission.edit', ['thesisSubmissionId' => $thesisSubmission->id,'submissionPostId' => $submissionPost->id]) }}" class="btn btn-warning btn-common">

            <i class="fas fa-pencil-alt">
            </i>
            Edit Submission
        </a>

        <form action="{{ route('thesisSubmission.delete',$thesisSubmission->id) }}" method="POST">

        <button type="button" class="btn btn-danger btn-common" data-toggle="modal" data-target="#deleteModal{{ $thesisSubmission->id }}">
            <i class="fas fa-trash"></i>
            Delete Submission
        </button>
        {{-- delet confirmation modal --}}
        <div class="modal fade" id="deleteModal{{ $thesisSubmission->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="top:50%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete the whole thesis submission?
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
    </form>
    </div>

    @else
        <a href="{{ route('stuThesisSubmission.create',['submission_post_id' =>  $submissionPost->id]) }}" class="btn btn-primary btn-common"><i class="fas fa-plus"></i> Add Submission</a>
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
    {{-- <pre>
        {{ print_r($thesisSubmission, true) }}
    </pre> --}}
    <div class = "card" style="margin-top: 16px">
        <table id="view1" class="custom-table generaltable table-bordered table table-striped" style="margin-left: 20px; margin-right: 20px; width:95%">

        @if ($thesisSubmission)

            @php
                $thesisFile = json_decode($thesisSubmission->thesis_file, true);
                $submissionStatus = (count($thesisFile) > 0) ? "Submitted" : "Pending";

                $submissionDeadline = \Carbon\Carbon::parse($submissionPost->submission_deadline);
                $currentTime = now();
                $deadlineTimeDiff  = $submissionDeadline->diff($currentTime);

                $fileSubmissionTime = \Carbon\Carbon::parse($thesisSubmission->updated_at); // Assuming 'updated_at' is a timestamp
                $fileTimeDiff = $fileSubmissionTime->diff($submissionDeadline);
                // Check if files exist
                $filesExist = !empty($thesisFile);
                $thesisTypes = explode(',', $thesisSubmission->thesis_type);

            @endphp

            <tbody>
                <tr>
                    <th style="width:25%;"><strong>Submission Status</strong></th>
                    <td style="margin:5px 10px; border-radius: 20px;" class="submission-status badge badge-success {{ $submissionStatus == 'Submitted' ? 'submitted' : 'pending' }}">{{ $submissionStatus }}</td>
                </tr>
                <tr>
                    <th><strong>Thesis Title</strong></th>
                    <td>{{ $thesisSubmission->thesis_title }}</td>
                </tr>
                <tr>
                    <th>Abstract:</th>
                    <td>{{ $thesisSubmission->thesis_abstract }}</td>
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
                                <p class="text-white">Assignment is submitted late by {{ $fileTimeDiff->format('%a days, %h hours, %i minutes') }}</p>
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
                    <th>
                        Thesis Type
                    </th>
                    <td>
                        @foreach($thesisTypes as $type)
                            @php
                                $displayName = '';
                                $backgroundColor = '';
                                $borderColor = '';

                                switch ($type) {
                                    case 'data_analytics':
                                        $displayName = 'Data Analytics';
                                        $backgroundColor = 'rgba(0, 0, 255, 0.2)'; // Blue with transparency
                                        $borderColor = 'blue';
                                        break;
                                    case 'web_development':
                                        $displayName = 'Web Development';
                                        $backgroundColor = 'rgba(0, 255, 0, 0.2)'; // Green with transparency
                                        $borderColor = 'green';
                                        break;
                                    case 'mobile_development':
                                        $displayName = 'Mobile Development';
                                        $backgroundColor = 'rgba(255, 0, 0, 0.2)'; // Red with transparency
                                        $borderColor = 'transparent';
                                        break;
                                    case 'machine_learning':
                                        $displayName = 'Machine Learning';
                                        $backgroundColor = '#FACD'; // Red with transparency
                                        $borderColor = '#FACD';
                                        break;
                                    // Add more cases for other types if needed
                                    default:
                                        $displayName = 'Unknown Type';
                                        $backgroundColor = 'rgba(128, 128, 128, 0.2)'; // Default color for unknown types with transparency
                                        $borderColor = 'gray';
                                        break;
                                }
                            @endphp

                            <span class="chip" style="border: 2px solid {{ $borderColor }}; background-color: {{ $backgroundColor }}; padding: 5px 10px; margin: 5px; border-radius: 20px;">
                                {{ $displayName }}
                            </span>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th><strong>Thesis File (only one file is allowed): </strong></th>
                    <td>
                        @if (is_array($thesisFile) && count($thesisFile) > 0)
                            @foreach ($thesisFile as $file)
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

                            </div>
                            <!--/.direct-chat-messages-->
            @endforeach

                                <div class="card-footer">
                                    <form method="POST" action="{{ route('stuThesisSubmission.addComment', ['thesisSubmissionId' => $thesisSubmission->id]) }}">
                                        @csrf
                                        <div class="input-group">
                                        <input type="text" name="comment_text" placeholder="Type Message ..." class="form-control">
                                        <span class="input-group-append">
                                            <button type="submit" class="btn btn-primary black-btn">Send</button>
                                        </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </ul>
                    </td>
                </tr>
            </tbody>
            </table>
            </div>
        @else
        <tbody>
            <tr>
                <th style="width:25%;"><strong>Submission Status</strong></th>
                {{-- <td>Pending</td> --}}
               <td>
                {{-- @if ($submissionDeadline->isFuture())
                    <span class="v-chip-column chip--label bg-light-green text-black">Pending</span>
                @else
                    <span class=" v-chip-column chip--label bg-light-red text-white">Overdue</span>
                @endif --}}

                @if ($submissionDeadline->isFuture())
                    {{-- <span class="v-chip-column chip--label bg-light-green text-black">Pending</span> --}}
                    <span class="status-pending">Pending</span>

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
                <td class="
                    @if ($submissionDeadline->isFuture())
                        bg-light-green text-black
                    @else
                        bg-light-red text-white
                    @endif
                ">
                    @if ($deadlineTimeDiff)
                        <p class="text-white">Assignment is overdue by {{ $deadlineTimeDiff->format('%a days, %h hours, %i minutes') }}</p>
                    @elseif($submissionDeadline->isFuture())
                        <p>{{ $deadlineTimeDiff->format('%a days, %h hours, %i minutes') }} left</p>
                    @endif
                </td>
            </tr>
            <tr>
                <th><strong>File Submission</strong></th>
                <td>-</td>
            </tr>
            <tr>
                <th><strong>Submission Comment</strong></th>
                <td>-</td>
            </tr>
        </tbody>
    </table>
        @endif

</main>

<script>
          $('#eventDetailsModal').fadeIn();
    </script>
@endsection