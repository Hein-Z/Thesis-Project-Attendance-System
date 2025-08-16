<?php

namespace App\Http\Controllers;
use App\Models\Teacher;
use App\Models\TeacherID;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeacherIDController extends Controller
{
    public function storeById($teacher_id)
{
    $now = Carbon::now();
    $today = $now->format('Y-m-d');

    // Daily schedule: time => teacher_id
    $dailySchedule = [
        '08:00-09:00' => 'T001',
        '09:00-10:00' => 'T002',
        '10:00-11:00' => 'T003',
        '11:00-12:00' => 'T004',
    ];

    // Find which schedule slot this teacher is in
    $slot = null;
    foreach ($dailySchedule as $timeRange => $id) {
        if ($id === $teacher_id) {
            $slot = $timeRange;
            break;
        }
    }

    if (!$slot) {
        return response()->json(['error' => 'Teacher not in today\'s schedule'], 404);
    }

    // Split start and end time
    [$startTime, $endTime] = explode('-', $slot);

    // Check if attendance already exists for this teacher today
    $attendanceIn = Teacher::where('teacher_id', $teacher_id)
        ->where('day', $today)
        ->where('status', 'In')
        ->first();

    $attendanceOut = Teacher::where('teacher_id', $teacher_id)
        ->where('day', $today)
        ->where('status', 'Out')
        ->first();

    $teacherInfo = TeacherID::find($teacher_id);

    if (!$attendanceIn) {
        // First scan -> mark In
        Teacher::create([
            'teacher_id' => $teacher_id,
            'day' => $today,
            'time' => $now,
            'status' => 'In',
        ]);

        // Update present times
        if ($teacherInfo) {
            $teacherInfo->increment('present_times');
        }

        return response()->json(['message' => 'Marked In']);
    } elseif (!$attendanceOut) {
        // Second scan -> mark Out
        Teacher::create([
            'teacher_id' => $teacher_id,
            'day' => $today,
            'time' => $now,
            'status' => 'Out',
        ]);

        return response()->json(['message' => 'Marked Out']);
    } else {
        return response()->json(['message' => 'Attendance already marked for today']);
    }
}

}
