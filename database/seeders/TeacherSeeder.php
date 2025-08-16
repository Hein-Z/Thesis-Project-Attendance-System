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
        DB::table('teachers')->insert([
            [
                'teacher_id' => 'T002',
                'check_in'   => Carbon::now('Asia/Yangon')->subDays(1)->setTime(9,0),
                'check_out'  => Carbon::now('Asia/Yangon')->subDays(1)->setTime(11,0),
                'checkout_type'     => 'manual',
                'created_at' => now('Asia/Yangon'),
                'updated_at' => now('Asia/Yangon'),
            ],
            [
                'teacher_id' => 'T002',
                'check_in'   => Carbon::now('Asia/Yangon')->subDays(1)->setTime(9,0),
                'check_out'  => Carbon::now('Asia/Yangon')->subDays(1)->setTime(11,0),
                'checkout_type'     => 'auto',
                'created_at' => now('Asia/Yangon'),
                'updated_at' => now('Asia/Yangon'),
            ],
        ]);
            }
        
    
}