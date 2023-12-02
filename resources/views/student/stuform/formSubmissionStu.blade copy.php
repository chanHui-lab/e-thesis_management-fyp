<!-- resources/views/submission/details.blade.php -->
@extends('student.studentpure')

@section('master_content')
<main>
    <h1 style="margin-top: 20px">{{ $submissionPost->title }}</h1>
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
                {{-- <span style="font-size: 80%; margin-left: 5px;">
                    {{ \Carbon\Carbon::parse($filee['uploaded_at'])->format('Y-m-d h:i A') }}
                </span> --}}
                        @endforeach
                @endif
            </div>
        </div>
    </div>
    <h3 style="margin-top: 20px">Form Submission Column</h3>
    @php
        $submissionDeadline = \Carbon\Carbon::parse($submissionPost->submission_deadline);
        $deadlineTimeDiff = $submissionDeadline->diff(now());

    @endphp
    @if ($formSubmission->isEmpty())

        {{-- <a href="{{ route('stuFormSubmission.create') }}" class="btn btn-primary">Add Submission</a> --}}
        <a href="{{ route('stuFormSubmission.create',['submission_post_id' =>  $submissionPost->id]) }}" class="btn btn-primary">Add Submission</a>
    @else
        {{-- @foreach($formSubmissions as $formSubmission) --}}

        <div class = "row row-buttonform" style="margin-left: 5px">
        {{-- <button class="btn btn-warning">Edit Submission</button> --}}
        {{-- <a class="btn btn-warning" href="{{ route('formpost.edit',$postform->id) }}"> --}}
        <a href="{{ route('formSubmission.edit', ['formSubmissionId' => $formSubmission->id]) }}" class="btn btn-warning">

        {{-- <a class="btn btn-info btn-sm" href="{{ route('formpost.edit',$postform->id) }}"> --}}
            <i class="fas fa-pencil-alt">
            </i>
            Edit Submission
        </a>

        {{-- delete parteu --}}
        <form action="{{ route('formSubmission.delete',$formSubmission->id) }}" method="POST">

        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $formSubmission->id }}">
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
    @endif
    <table id="view1" class="custom-table generaltable table-bordered table table-striped">

    @forelse($formSubmission as $formSubmissionee)

    @php
        $formfiles = json_decode($formSubmissionee->form_files, true);
        $submissionStatus = (count($formfiles) > 0) ? "Submitted" : "Pending";

        $submissionDeadline = \Carbon\Carbon::parse($submissionPost->submission_deadline);
        $currentTime = now();
        $deadlineTimeDiff  = $submissionDeadline->diff($currentTime);

        $fileSubmissionTime = \Carbon\Carbon::parse($formSubmissionee->updated_at); // Assuming 'updated_at' is a timestamp
        $fileTimeDiff = $fileSubmissionTime->diff($submissionDeadline);
        // Check if files exist
        $filesExist = !empty($formfiles);
    @endphp


        <tbody>
            <tr>
                <th style="width:25%;"><strong>Submission Status</strong></th>
                <td class="submission-status {{ $submissionStatus == 'Submitted' ? 'submitted' : 'pending' }}">{{ $submissionStatus }}</td>
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
                                @elseif (Str::endsWith($file['path'], '.doc') || Str::endsWith($file['path'], '.docx'))
                                    <i class="fa fa-file-word file-icon" style="color: rgb(77, 144, 250)"></i>
                                @else
                                    <i class="fa fa-file file-icon" style="color: rgb(77, 144, 250)"></i>
                                @endif
                                {{ substr($file['path'], strpos($file['path'], '_') + 1) }}
                            </a>
                            <span style="font-size: 80%; margin-left: 5px;">
                                {{ \Carbon\Carbon::parse($file['uploaded_at'])->format('Y-m-d h:i A') }}
                            </span><br>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <th><strong>Submission Comment</strong></th>
                <td>{{ $formSubmission->form_title }}</td>
            </tr>
        </tbody>
    </table>

@empty

        <tbody>
            <tr>
                <th style="width:25%;"><strong>Submission Status</strong></th>
                {{-- <td>Pending</td> --}}
               <td>
                @if ($submissionDeadline->isFuture())
                    <span class="v-chip-column chip--label bg-light-green text-black">Pending</span>
                @else
                    <span class=" v-chip-column chip--label bg-light-red text-white">Overdue</span>
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
@endforelse
</main>
@endsection

<style>

</style>
