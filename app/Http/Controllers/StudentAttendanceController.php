<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Teacher;
use App\Models\TeacherID;
use App\Models\Student;

class StudentAttendanceController extends Controller
{
    public function markAttendance($student_id)
    {
       // Student check-in
   
        $today = Carbon::now('Asia/Yangon')->toDateString();

        $teacher = Teacher::whereDate('check_in', $today)
            ->whereNull('check_out')
            ->first();

        if (!$teacher) {
            return response()->json(['message' => 'No teacher session active',
        'status'=>'No Class'
        ], 403);
        }

        // Prevent duplicate check-in
        $already = Student::where('student_id', $student_id)
            ->where('teacher_id', $teacher->teacher_id)
            ->whereDate('date', $today)
            ->first();

        if ($already ) {
            DB::table('students')
            ->where('id', $already->id)
            ->update([
                'status'     => 'Present',
                'check_in'   => Carbon::now('Asia/Yangon'),
                'updated_at' => Carbon::now(),
            ]);
            return response()->json(['message' => "Attendance updated for student {$student_id}",
        'status'=>'Updated'
        ]);
        }

        Student::create([
            'student_id' => $student_id,
            'teacher_id' => $teacher->teacher_id,
            'check_in'   => Carbon::now('Asia/Yangon'),
            'status'     => 'Present',
            'date'       => $today
        ]);

        return response()->json(['message' => 'Student checked in successfully',
    'status'=>'In'
    ]);
    }
}
    