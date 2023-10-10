@extends('layouts.studentdash')
@section('content')
<section id="content">
<h1>Dashboard</h1>
</section>
{{-- <section id="content">
    <main>
    <div class="container">
        <h1>Dashboard</h1>

        @if ($reminders->isEmpty())
            <p>No reminders found.</p>
        @else
            <ul>
                @foreach ($reminders as $producthehe)
                    <li>
                        <strong>Reminder:</strong> {{ $producthehe->name }}
                        <strong>Due Date:</strong> {{ $producthehe->submission_deadline->format('Y-m-d H:i') }}
                        <strong>Remaining Time:</strong> {{ $producthehe->remainingDays }} days {{ $producthehe->remainingHours }} hours
                        <br>
                        <br>

                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    </main>
    </section> --}}
@endsection