<?php

namespace App\Http\Controllers;
use App\Models\Student;
use App\Models\StudentID;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
class StudentController extends Controller
{
    public function show(): View
    {
        $students = Student::with(['student_info', 'teacher_info'])
        ->orderBy('date', 'desc')
        ->get();
        return view('student', compact('students'));
    }

     public function profile($student_id)
    {
        // Load student info with all related attendance records and teacher info
        $student = StudentID::with('attendances.teacher_info')
                    ->where('student_id', $student_id)
                    ->firstOrFail();

        return view('studentProfile', compact('student'));
    }
    public function latest()
{
    $today = Carbon::now()->toDateString();

    $student = Student::whereDate('updated_at', $today)->where('status','Present')->with('student_info')->with('teacher_info')
        ->orderBy('updated_at', 'desc')
        ->first();

       if ($student) {
        return response()->json([
            "student"=>$student
        ]);
    }
    return response()->json(null);
}
}
