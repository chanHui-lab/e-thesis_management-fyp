<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Student;
use App\Models\Supervisor;
use Illuminate\Support\Facades\Auth;

class SupervisorStudentAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loggedInUserId = Auth::user()->id;

        $students = Student::with('user')->get();
        $advisors = Supervisor::with(['user', 'student'])->get();

        $assignedStudents = Student::with('supervisor.user')->get();

        $mystudent = Student::whereHas('supervisor', function ($query) use ($loggedInUserId) {
            $query->where('lecturer_id', $loggedInUserId);
        })
        ->with('supervisor.user')
        ->get();
        // dd( $mystudent);

        return view('lecturer.supervisedstudent', compact('students', 'advisors','assignedStudents','mystudent' ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::with('user')->get();
        $advisors = Supervisor::with(['user', 'student'])->get();

        $assignedStudents = Student::with('supervisor.user')->get();

        return view('admin.advisor-assignment.create', compact('students', 'advisors','assignedStudents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $student = Student::find($request->input('stu_id'));
    //     $student->supervisor_id = $request->input('supervisor_id');
    //     $student->save();
    //     return redirect()->route('admin.advisor-assignment.create')->with('success', 'Advisor assigned successfully');
    // }

    public function store(Request $request)
{
    $studentId = $request->input('stu_id');
    $lecturerId = $request->input('lecturer_id');

    // Find the student
    $student = Student::find($studentId);
    // dd($student);
    if ($student) {
        // Update the supervisor_id for the student
        $student->update(['supervisor_id' => $lecturerId]);

        return redirect()->back()->with('success', 'Lecturer assigned successfully.');
    } else {
        return redirect()->back()->with('error', 'Student not found.');
    }
}

    // public function displayAll()
    // {
    //     // Fetch assigned students and their assigned lecturers
    //     $assignedStudents = Student::with('supervisor.user')->get();

    //     return view('admin.assignment.create', compact('assignedStudents'));
    // }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
