<?php

namespace App\Http\Controllers;
use App\Models\Teacher;
use App\Models\TeacherID;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentID;
use App\Events\TeacherCheckedIn;

class TeacherIDController extends Controller
{
    public function storeById($teacher_id)
{
    $now = Carbon::now('Asia/Yangon');  // Myanmar timezone
    $today = $now->toDateString();

    // Find today's session for this teacher that is not checked out yet
    $teacherSession = Teacher::where('teacher_id', $teacher_id)
        ->whereDate('check_in', $today)
        ->whereNull('check_out')
        ->first();

    if (!$teacherSession) {
        // ✅ Teacher IN (first scan)

        // Check if another teacher is already in the class
        $active = Teacher::whereDate('check_in', $today)
            ->whereNull('check_out')
            ->where('teacher_id', '!=', $teacher_id) // exclude same teacher
            ->first();

        if ($active) {
            $checkInTime = Carbon::parse($active->check_in, 'Asia/Yangon');
            
            // 1-hour limit
            if ($checkInTime->diffInMinutes($now) < 1) {
                return response()->json([
                    'error' => 'Another teacher is already inside (less than 1 hour)'
                ], 403);
            } else {
        $allStudents = StudentID::pluck('student_id')->toArray();
        $presentStudents = Student::where('teacher_id', $active->teacher_id)
        ->whereDate('date', $today)
        ->pluck('student_id')
        ->toArray();

    $absentStudents = array_diff($allStudents, $presentStudents);

    foreach ($absentStudents as $sid) {
        Student::create([
            'student_id' => $sid,
            'teacher_id' => $active->teacher_id,
            'status'     => 'Absent',
            'date'       => $today
        ]);
    }
                // Auto checkout previous teacher after 1 hour
               $active->update([
                    'check_out'     => $checkInTime->copy()->addMinutes(30),
                    'checkout_type' => 'auto'
                ]);
             

            }
        }

        // Store new teacher session
      $attendance=  Teacher::create([
            'teacher_id' => $teacher_id,
            'check_in'   => $now
        ]);
//   broadcast(new TeacherCheckedIn($attendance))->toOthers();

        return response()->json([
            'message' => 'Teacher checked in successfully',
            'status'=>'In'
        ]);
    } else {
        // ✅ Teacher OUT (second scan)
        $teacherSession->update([
            'check_out'     => $now,
            'checkout_type' => 'manual'
        ]);
    $attendance=$teacherSession;
//   broadcast(new TeacherCheckedIn($attendance))->toOthers();
        // Mark absentees: students who did not check in
        $allStudents = StudentID::pluck('student_id')->toArray();
        $presentStudents = Student::where('teacher_id', $teacher_id)
            ->whereDate('date', $today)
            ->pluck('student_id')
            ->toArray();

        $absentStudents = array_diff($allStudents, $presentStudents);

        foreach ($absentStudents as $sid) {
            Student::create([
                'student_id' => $sid,
                'teacher_id' => $teacher_id,
                'status'     => 'Absent',
                'date'       => $today
            ]);
        }
//   broadcast(new TeacherCheckedIn($attendance))->toOthers();

        return response()->json([
            'message' => 'Teacher checked out & absentees marked',
            'status'=>'Out'
        ]);
    }
}
}