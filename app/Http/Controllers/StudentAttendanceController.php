<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class StudentAttendanceController extends Controller
{
    public function markAttendance($student_id)
    {
        // Myanmar timezone
        $now = Carbon::now('Asia/Yangon');
        $currentTime = $now->format('H:i');
        $today = $now->toDateString();
        $currentDay = $now->format('l'); // Monday, Tuesday, etc.
    
        // Repeating timetable for every week
        $timetable = [
            ['T001', 'Daw Mary', 'English', '08:00', '09:00', ['Monday','Tuesday','Wednesday','Thursday','Friday']],
            ['T002', 'Daw Thida', 'Math', '09:00', '10:00', ['Monday','Tuesday','Wednesday','Thursday','Friday']],
            ['T004', 'U Sai Myo', 'Physics', '10:00', '11:00', ['Monday','Tuesday','Wednesday','Thursday','Friday']],
            ['T003', 'Daw Thida', 'Chemistry', '11:00', '12:00', ['Monday','Tuesday','Wednesday','Thursday','Friday']],
        ];
    
        foreach ($timetable as $class) {
            [$teacherId, $teacherName, $subject, $start, $end, $days] = $class;
    
            // Check if today matches class schedule day
            if (in_array($currentDay, $days) && $currentTime >= $start && $currentTime <= $end) {
    
                // Check if attendance already exists for this student, class, and date
                $exists = DB::table('student_attendances')
                    ->where('student_id', $student_id)
                    ->where('teacher_id', $teacherId)
                    ->where('date', $today)
                    ->exists();
    
                if (!$exists) {
                    DB::table('student_attendances')->insert([
                        'student_id' => $student_id,
                        'teacher_id' => $teacherId,
                        'subject' => $subject,
                        'class_start' => $start,
                        'class_end' => $end,
                        'status' => 'Present',
                        'check_in' => $now->format('H:i'),
                        'date' => $today,
                        'created_at' => now('Asia/Yangon'),
                        'updated_at' => now('Asia/Yangon'),
                    ]);
    
                    return response()->json([
                        'success' => true,
                        'message' => "Attendance marked for $student_id in $subject class.",
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => "Already marked for this class today.",
                    ]);
                }
            }
        }
    
        return response()->json([
            'success' => false,
            'message' => "No active class right now for $student_id.",
        ]);
    }
}