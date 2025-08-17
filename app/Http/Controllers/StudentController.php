<?php

namespace App\Http\Controllers;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;
class StudentController extends Controller
{
    public function show(): View
    {
        $students = Student::with(['student_info', 'teacher_info'])
        ->orderBy('date', 'desc')
        ->get();
        return view('student', compact('students'));
    }

  
}
