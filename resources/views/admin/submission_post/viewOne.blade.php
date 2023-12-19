@extends('admin.template_page.adminpure')

@section('master_content')

<div class="submission-tab">
    {{-- <div class="tab-button"> --}}
        <a class="btn btn-primary back-button" href="{{ route('formpost.showAll', ['submissionPostId' => $submissionPostId]) }}">Back</a>
        {{-- <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('formpost.index') }}"> Back</a>
          </div> --}}
    {{-- </div> --}}
    <div class="tab-content">
        <p> Submitted by:  <strong>{{ $formSubmission->student->matric_number }} {{ $formSubmission->student->user->name }}</strong></p>
    </div>
</div>

    <h1 style="margin-top: 20px">{{ $formSubmission->submissionPost->title }}</h1>
    <p>{{ $formSubmission->submissionPost->description }} </p>

    <div class="card">

        <div class="card-body custom-gray-box">
            <p><strong>Opened:</strong> {{ $formSubmission->submissionPost->created_at }}</p>
            <p class="linee"><strong>Due:</strong> {{ $formSubmission->submissionPost->submission_deadline }}</p>
            @php
                $files = json_decode($formSubmission->submissionPost->files, true);
            @endphp
            <div class="arrow-box">

                @if (is_array($files) && count($files) > 0)
                &#x2514;

                @foreach ($files as $filee)
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
                        @endforeach
                @endif

        </div>
        </div>
    </div>

    @php
        $formfiles = json_decode($formSubmission->form_files, true);
        $submissionStatus = (count($formfiles) > 0) ? "Submitted" : "Pending";

        $submissionDeadline = \Carbon\Carbon::parse($formSubmission->submissionPost->submission_deadline);
        $currentTime = now();
        $deadlineTimeDiff  = $submissionDeadline->diff($currentTime);

        $fileSubmissionTime = \Carbon\Carbon::parse($formSubmission->updated_at); // Assuming 'updated_at' is a timestamp
        $fileTimeDiff = $fileSubmissionTime->diff($submissionDeadline);
        // Check if files exist
        $filesExist = !empty($formfiles);

    @endphp

        <table id="view1" class="custom-table generaltable table-bordered table table-striped">
            <tbody>
                <tr>
                    <th style="width:25%;"><strong>Submission Status</strong></th>
                    <td class="submission-status {{ $submissionStatus == 'Submitted' ? 'submitted' : 'pending' }}"> {{ $submissionStatus }}</td>
                </tr>
                <tr>
                    <th ><strong>Form Title</strong></th>
                    <td> {{ $formSubmission->form_title }}</td>
                </tr>
                <tr>
                    <th>Description:</th>
                    <td>{{ $formSubmission->description }}</td>
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
                        <td> {{ $formSubmission->form_title }}</td>
                    </tr>

            </tbody>
            {{-- @endforeach
            @endif --}}

        </table>

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
