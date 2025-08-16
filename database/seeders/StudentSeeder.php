<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\StudentID;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Set Myanmar timezone
        $today = Carbon::now('Asia/Yangon')->toDateString();

        // Example timetable
        $timetable = [
            ['teacher_id' => 'T001', 'subject' => 'English', 'class_start' => '08:00', 'class_end' => '09:00'],
            ['teacher_id' => 'T002', 'subject' => 'Math', 'class_start' => '09:00', 'class_end' => '10:00'],
            ['teacher_id' => 'T003', 'subject' => 'Physics', 'class_start' => '10:00', 'class_end' => '11:00'],
            ['teacher_id' => 'T004', 'subject' => 'Chemistry', 'class_start' => '11:00', 'class_end' => '12:00'],
        ];

        $students = StudentId::all();

        foreach ($timetable as $class) {
            foreach ($students as $student) {
                // Randomly decide if present or absent (you can modify this logic)
                $status = rand(0, 1) ? 'Present' : 'Absent';
                $check_in = $status === 'Present' ? Carbon::parse($class['class_start'])->addMinutes(rand(0, 5))->format('H:i:s') : null;

                DB::table('students')->insert([
                    'student_id'   => $student->student_id,
                    'teacher_id'   => $class['teacher_id'],
                    'subject'      => $class['subject'],
                    'class_start'  => $class['class_start'],
                    'class_end'    => $class['class_end'],
                    'status'       => $status,
                    'check_in'     => $check_in,
                    'date'         => $today,
                    'created_at'   => now('Asia/Yangon'),
                    'updated_at'   => now('Asia/Yangon'),
                ]);
           
            }
        }
    
    }
}