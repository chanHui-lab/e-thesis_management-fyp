@extends('admin.template_page.adminpure')

@section('master_content')

<div class="submission-tab">
    <div class="tab-button">
        <button class="back-button">Back</button>
    </div>
    <div class="tab-content">
        <p> Submitted by: <strong>{{ $student->matric_number }} {{ $student->user->name }}</strong></p>
    </div>
</div>

    <h1 style="margin-top: 20px">{{ $submissionPost->title }}</h1>
    {{--<table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Form Title</th>
                <th>Form Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($formSubmission as $submission)
                <tr>
                    @if ($submission)
                        <td>{{ $student->name }}</td>
                    @else
                        <td>Form Submission not found</td>
                    @endif
                    {{-- <td>{{ $submission->form_title }}</td>
                    <td>{{ $submission->form_date }}</td> --}}
                {{-- </tr>
            @endforeach
        </tbody>
    </table> --}}
    <p>{{ $submissionPost->description }} </p>

    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">Submission Details</h3>
        </div> --}}
        <div class="card-body custom-gray-box">
            <p><strong>Opened:</strong> {{ $submissionPost->created_at }}</p>
            <p class="linee"><strong>Due:</strong> {{ $submissionPost->submission_deadline }}</p>
            @php
                $files = json_decode($submissionPost->files, true);
            @endphp
            <div class="arrow-box">

                @if (is_array($files) && count($files) > 0)
                &#x2514;

                @foreach ($files as $file)
                <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" download class="downloadfile-link">
                    @if (Str::endsWith($file['path'], '.pdf'))
                    <i class="fa fa-file-pdf file-icon" style = "color:  rgb(255, 86, 86)"></i>

                    @elseif (Str::endsWith($file['path'], '.doc') || Str::endsWith($file['path'], '.docx'))
                    <i class="fa fa-file-word file-icon" style = "color: rgb(77, 144, 250)"></i>

                    @else
                    <i class="fa fa-solid fa-file file-icon" style = "color: rgb(77, 144, 250)"></i>
                    @endif
                    {{ substr($file['path'], strpos($file['path'], '_') + 1) }}

                </a >
                <span style="font-size: 80%; margin-left: 5px;">
                    {{ \Carbon\Carbon::parse($file['uploaded_at'])->format('Y-m-d h:i A') }}
                </span>
                        @endforeach
                @endif
{{--
            @foreach (json_decode($formSubmission->form_files) as $file)
                <li>Path: {{ $file->path }}</li>
                <li>Uploaded At: {{ $file->uploaded_at }}</li>
            @endforeach --}}
        </div>
        </div>
    </div>

    @php
        $formfiles = json_decode($formSubmission->form_files, true);
        $submissionStatus = (count($formfiles) > 0) ? "Submitted" : "Pending";
    @endphp

        <table id="view1" class="custom-table generaltable table-bordered table table-striped">
            <tbody>
                <tr>
                    <th ><strong>Submission Status</strong></th>
                    <td class="submission-status {{ $submissionStatus == 'Submitted' ? 'submitted' : 'pending' }}"> {{ $submissionStatus }}</td>
                </tr>
                <tr>
                    <th ><strong>Form Title</strong></th>
                    <td> {{ $formSubmission->form_title }}</td>
                </tr>
                <tr>
                    <th>Description:</th>
                    <td>{{ $formSubmission->form_title }}</td>
                </tr>
                <tr>
                    <th><strong>Time Remaining</strong></th>
                    <td> {{ $formSubmission->form_title }}</td>
                </tr>
                <tr>
                    @if (is_array($formfiles) && count($formfiles) > 0)
                    @foreach ($formfiles as $file)
                    <th><strong>File Submission</strong></th>
                    <td>
                    <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" download class="downloadfile-link">
                        @if (Str::endsWith($file['path'], '.pdf'))
                        <i class="fa fa-file-pdf file-icon" style = "color:  rgb(255, 86, 86)"></i>

                        @elseif (Str::endsWith($file['path'], '.doc') || Str::endsWith($file['path'], '.docx'))
                        <i class="fa fa-file-word file-icon" style = "color: rgb(77, 144, 250)"></i>

                        @else
                        <i class="fa fa-file file-icon" style = "color: rgb(77, 144, 250)"></i>
                        @endif
                        {{ substr($file['path'], strpos($file['path'], '_') + 1) }}
                    </a></td>
                </tr>
                <tr>
                    <th><strong>Submission Comment</strong></th>
                    <td> {{ $formSubmission->form_title }}</td>
                </tr>
            </tbody>
            @endforeach
            @endif

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
