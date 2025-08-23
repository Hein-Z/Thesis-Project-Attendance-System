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
        $students = [];
        for ($i = 1; $i <= 17; $i++) {
            $students[] = 'S' . str_pad($i, 3, '0', STR_PAD_LEFT);
        }

        $teachers = ['T001','T002','T003','T004'];

        $startDate = Carbon::create(2025, 7, 17, 0, 0, 0, 'Asia/Yangon');
        $endDate   = Carbon::create(2025, 8, 17, 0, 0, 0, 'Asia/Yangon');

        $date = $startDate->copy();

        while ($date->lte($endDate)) {
            // Skip weekends
            if (!in_array($date->dayOfWeekIso, [6, 7])) {

                foreach ($teachers as $teacher_id) {
                    // Get teacher attendance for this day
                    $teacherAttendance = DB::table('teachers')
                        ->where('teacher_id', $teacher_id)
                        ->whereDate('check_in', $date->toDateString())
                        ->first();

                    $classStart = $teacherAttendance && $teacherAttendance->check_in
                        ? Carbon::parse($teacherAttendance->check_in, 'Asia/Yangon')
                        : Carbon::createFromTime(8 + array_search($teacher_id, $teachers), 0, 0, 'Asia/Yangon'); // fallback

                    $classEnd = $teacherAttendance && $teacherAttendance->check_out
                        ? Carbon::parse($teacherAttendance->check_out, 'Asia/Yangon')
                        : $classStart->copy()->addHour();

                    foreach ($students as $student_id) {
                        // 80% chance present, 20% absent
                        $isPresent = rand(1, 100) <= 80;

                        $status  = $isPresent ? 'Present' : 'Absent';
                        $checkIn = null;

                        if ($isPresent) {
                            $durationMinutes = $classEnd->diffInMinutes($classStart);

                            // Random late
                            $isLateStudent = rand(1, 100) <= 40;

                            if ($isLateStudent) {
                                $minMinute = 10; // minimum 10 mins late
                                $maxMinute = $durationMinutes - 1;
                            } else {
                                $minMinute = 0;
                                $maxMinute = max(9, intval($durationMinutes * 2 / 3)); // on time within first 2/3
                            }

                            $offset = rand($minMinute, $maxMinute);
                            $checkIn = $classStart->copy()->addMinutes($offset);

                            // Update status if late 10+ minutes
                            if ($offset >= 10) {
                                $status = 'Late';
                            }
                        }

                       // Set created_at / updated_at = date + check_in time
                        $datetime = $checkIn
                            ? $date->format('Y-m-d') . ' ' . $checkIn->format('H:i:s')
                            : $date->format('Y-m-d') . ' ' . $classStart->format('H:i:s'); // fallback if absent

                        DB::table('students')->insert([
                            'student_id' => $student_id,
                            'teacher_id' => $teacher_id,
                            'status'     => $status,
                            'date'       => $date->toDateString(),
                            'check_in'   => $checkIn ? $checkIn->format('H:i:s') : $classEnd->format('H:i:s'),
                            'created_at' => $datetime,
                            'updated_at' => $datetime,
                        ]);
                    }

                }
            }

            $date->addDay();
        }
    }
}