{{-- <!-- resources/views/lecturer/submission_posts.blade.php -->
@extends('admin.template_page.adminpure')

@section('master_content')

    <div class="container">
        <h1>Submission Posts for Your Students</h1>

        @if ($submissionPosts->isEmpty())
            <p>No submission posts available.</p>
        @else
            <ul>
                @foreach ($submissionPosts as $post)
                    <li>
                        <!-- Display submission post details as needed -->
                        Title: {{ $post->title }}
                        Description: {{ $post->description }}
                        Submission Deadline: {{ $post->submission_deadline }}
                        <!-- Add more submission post details here -->
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection --}}
@extends('admin.template_page.adminpure')

@section('master_content')
<h1>{{ $submissionPost->title }}</h1>

<table>
    <thead>
        <tr>
            <th>Student</th>
            <th>Matric Number</th>
            <th>Form Title</th>
            <th>Form Uploaded Date</th>
            <!-- Add more columns as needed -->
        </tr>
    </thead>
    <tbody>
        @foreach ($formSubmissions as $submission)
            <tr>
                <td>{{ $submission->student->stu_id }}</td>
                <td>{{ $submission->student->matric_number }}</td>
                <td>{{ $submission->form_title }}</td>
                <td>{{ $submission->form_date }}</td>
                <!-- Add more table cells as needed -->
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
