<?php

namespace App\Http\Controllers;
use App\Models\Teacher;
use App\Models\TeacherID;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentID;
use App\Events\TeacherCheckedIn;
use Illuminate\Support\Facades\Log;
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
            ->where('teacher_id', '!=', $teacher_id)
            ->first();

        if ($active) {
$checkInTime = Carbon::parse($active->check_in)->timezone('Asia/Yangon');
$now = Carbon::now('Asia/Yangon');

$minutesDiff = $checkInTime->diffInMinutes($now, false);

Log::info("Check-in: " . $checkInTime->format('Y-m-d H:i:s'));
Log::info("Now: " . $now->format('Y-m-d H:i:s'));
Log::info("Minutes difference: " . $minutesDiff);

            if ($minutesDiff < 1) {
                Log::info($minutesDiff);
                // 🚫 Block entry if less than 1 hour
                return response()->json([
                    'error'  => 'Another teacher is already inside (less than 1 hour)',
                    'status' => 'Not',
                    'time'   => $now->format('h:i A')
                ]);
            } else {
                // ✅ Auto-checkout previous teacher + mark absentees

                // Checkout time is *30 mins after their check_in*
                $checkoutTime = $checkInTime->copy()->addMinutes(30);

                $active->update([
                    'check_out'     => $checkoutTime,
                    'checkout_type' => 'auto'
                ]);

                // 🔔 Mark absentees
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
                        'date'       => $today,
                        'check_in'   => $checkoutTime,
                    ]);
                }
            }
        }

        // Store new teacher session
        Teacher::create([
            'teacher_id' => $teacher_id,
            'check_in'   => $now
        ]);

        return response()->json([
            'message' => 'Teacher checked in successfully',
            'status'  => 'In',
            'time'    => $now->format('h:i A')
        ]);
    } else {
        // ✅ Teacher OUT (second scan)
        $teacherSession->update([
            'check_out'     => $now,
            'checkout_type' => 'manual',
        ]);

        // 🔹 Check if this teacher has another session later today
        $hasAnotherSession = Teacher::where('teacher_id', $teacher_id)
            ->whereDate('check_in', $today)
            ->whereNull('check_out')
            ->exists();

        if (!$hasAnotherSession) {
            // Only mark absentees if no other session exists today
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
        }

        return response()->json([
            'message' => $hasAnotherSession 
                ? 'Teacher checked out (students not marked absent yet, waiting for next session)'
                : 'Teacher checked out & absentees marked',
            'status'  => 'Out',
            'time'    => $now->format('h:i A')
        ]);
    }
}

}