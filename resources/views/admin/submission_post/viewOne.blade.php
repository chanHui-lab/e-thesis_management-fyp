@extends('admin.template_page.adminpure')

@section('master_content')

    <h1>Form Submissions for "{{ $submissionPost->title }}"</h1>
    <table>
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
                    <td>{{ $submission->student->user->name }}</td>
                    <td>{{ $submission->form_title }}</td>
                    <td>{{ $submission->form_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
