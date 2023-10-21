@extends('admin.template_page.adminpure')

@section('master_content')

<main>

    <h1>Assign Advisors to Students</h1>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <form action="{{ route('advisor-assignment.store') }}" method="POST">
        @csrf
        <label for="stu_id">Select a Student:</label>
        <select name="stu_id" id="stu_id">
            @foreach ($students as $student)
                <option value="{{ $student->stu_id }}">
                    {{ $student->matric_number }} - {{ $student->user->name }}
                </option>
            @endforeach
        </select>

        <br>

        <label for="lecturer_id">Select an Advisor:</label>
        <select name="lecturer_id" id="lecturer_id">
            @foreach ($advisors as $advisor)
                <option value="{{ $advisor->lecturer_id }}">{{ $advisor->user->name }}</option>
            @endforeach
        </select>

        <br>

        <button type="submit">Assign Advisor</button>
    </form>

    <h2>Assigned Students</h2>
    <table id="example1" class="table table-bordered table-striped">
        <thead>
    {{-- <table>
        <thead> --}}
            <tr>
                <th>No.</th>
                <th>Matric Number</th>
                <th>Student's Name</th>
                <th>Supervisor</th>
            </tr>
        </thead>
        <tbody>
            @php
                        $counter = 1; // Initialize a counter variable
                    @endphp
            @foreach ($assignedStudents as $student)
                <tr>
                    <td>{{ $counter++ }}</td>
                    <td>{{ $student->matric_number }}</td>
                    <td>{{ $student->user->name }}</td>
                    <td>{{ $student->supervisor->user->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</main>
@endsection