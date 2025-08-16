<?php

namespace App\Console\Commands;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Console\Command;

class MaskAbsentSubjects extends Command
{
    
    protected $signature = 'attendance:mark-absent';
    protected $description = 'Mark absent for students who did not check in after class ends';

    public function handle()
    {
        try {
        $now = Carbon::now('Asia/Yangon');
        $today = $now->toDateString();
        $currentDay = $now->format('l');

        // Define timetable
        $timetable = [
            ['T001', 'English', '08:00', '09:00', ['Monday','Tuesday','Wednesday','Thursday','Friday']],
            ['T002', 'Math', '09:00', '10:00', ['Monday','Tuesday','Wednesday','Thursday','Friday']],
            ['T004', 'Physics', '10:00', '11:00', ['Monday','Tuesday','Wednesday','Thursday','Friday']],
            ['T003', 'Chemistry', '11:00', '12:00', ['Monday','Tuesday','Wednesday','Thursday','Friday']],
        ];

        foreach ($timetable as $class) {
            [$teacherId, $subject, $start, $end, $days] = $class;

            // Only process if today is class day and current time is past class end
            if (in_array($currentDay, $days) && $now->format('H:i') > $end) {
                // Get all students who haven't checked in for this class today
                $studentsWithoutAttendance = DB::table('student_ids')
                    ->whereNotIn('student_id', function ($query) use ($teacherId, $today) {
                        $query->select('student_id')
                            ->from('students')
                            ->where('teacher_id', $teacherId)
                            ->where('date', $today);
                    })
                    ->get();

                foreach ($studentsWithoutAttendance as $student) {
                    DB::table('students')->insert([
                        'student_id' => $student->student_id,
                        'teacher_id' => $teacherId,
                        'subject' => $subject,
                        'class_start' => $start,
                        'class_end' => $end,
                        'status' => 'Absent',
                        'check_in' => null,
                        'date' => $today,
                        'created_at' => now('Asia/Yangon'),
                        'updated_at' => now('Asia/Yangon'),
                    ]);
                }
            }
        }

        $this->info('Absent students marked successfully.');
    } catch (\Exception $e) {
        Log::error('MaskAbsentSubjects Error: '.$e->getMessage());
    }
    }
}
