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
       $teachers = ['T001','T002','T003','T004'];

        $checkoutTypes = ['manual', 'auto', 'changed by admin'];

        $startDate = Carbon::create(2025, 7, 25); // Start seeding from 25 July 2025
        $endDate   = Carbon::create(2025, 8, 16); // Up to 16 August 2025 (not including 17th)

        foreach ($teachers as $teacher_id) {
            $date = $startDate->copy();

            while ($date->lte($endDate)) {
                // Random number of sessions per day (1-3)
                $sessions = rand(1, 3);

                for ($i = 0; $i < $sessions; $i++) {
                    $checkIn = $date->copy()->addHours(rand(7, 15))->addMinutes(rand(0, 59));
                    $durationMinutes = rand(20, 120);
                    $checkOut = $checkIn->copy()->addMinutes($durationMinutes);
                    $checkoutType = $checkoutTypes[array_rand($checkoutTypes)];

                    DB::table('teachers')->insert([
                        'teacher_id'    => $teacher_id,
                        'check_in'      => $checkIn,
                        'check_out'     => $checkOut,
                        'checkout_type' => $checkoutType,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                }

                $date->addDay();
            }
        }
    }
    
}