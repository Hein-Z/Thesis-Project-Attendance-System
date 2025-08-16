<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TeacherIDSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks to allow truncating safely
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('teacher_id')->truncate(); 
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $teachers = [
            ['T001', 'Daw Aye', 'English', 100, 5],
            ['T002', 'Daw Mya', 'Math', 104, 1],
            ['T003', 'U Zaw', 'Physics', 103, 2],
            ['T004', 'Daw Hla', 'Chemistry', 104, 1],
        ];

        $now = Carbon::now();

        foreach ($teachers as $teacher) {
            DB::table('teacher_id')->insert([
                'id' => $teacher[0],
                'name'=> $teacher[1],
                'subject'=> $teacher[2],
                'present_times'=> $teacher[3],
                'absent_times'=> $teacher[4],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}