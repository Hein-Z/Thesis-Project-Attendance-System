<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        // Clear old attendance rows
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('teachers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $days = [
            'Monday'    => '2025-08-04',
            'Tuesday'   => '2025-08-05',
            'Wednesday' => '2025-08-06',
            'Thursday'  => '2025-08-07',
            'Friday'    => '2025-08-08',
        ];

        $teachers = [
            ['T001', '08:03', '08:55'],
            ['T002', '09:02', '09:59'],
            ['T003', '10:00', '10:57'],
            ['T004', '11:00', '11:59'],
        ];

        foreach ($teachers as $teacher) {
            foreach ($days as $dayName => $date) {
                $absent = rand(1, 10) <= 2;

                if ($absent) {
                    // Absent row
                    DB::table('teachers')->insert([
                        'teacher_id' => $teacher[0],
                        'day'        => $dayName,
                        'time'       => null,
                        'status'     => 'Absent',
                        'created_at' => Carbon::parse($date),
                        'updated_at' => Carbon::parse($date),
                    ]);
                } else {
                    // In row
                    DB::table('teachers')->insert([
                        'teacher_id' => $teacher[0],
                        'day'        => $dayName,
                        'time'       => Carbon::parse($teacher[1]),
                        'status'     => 'In',
                        'created_at' => Carbon::parse($date),
                        'updated_at' => Carbon::parse($date),
                    ]);

                    // Out row
                    DB::table('teachers')->insert([
                        'teacher_id' => $teacher[0],
                        'day'        => $dayName,
                        'time'       => Carbon::parse($teacher[2]),
                        'status'     => 'Out',
                        'created_at' => Carbon::parse($date),
                        'updated_at' => Carbon::parse($date),
                    ]);
                }
            }
        }
    }
}