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
        $teachers = [
            'T001' => '08:00', // 8am slot
            'T002' => '09:00', // 9am slot
            'T003' => '10:00', // 10am slot
            'T004' => '11:00', // 11am slot
        ];

        $checkoutTypes = ['manual', 'auto', 'changed by admin'];

        // Use Myanmar timezone
        $startDate = Carbon::create(2025, 5, 1, 0, 0, 0, 'Asia/Yangon');
        $endDate   = Carbon::create(2025, 8, 17, 23, 59, 59, 'Asia/Yangon');

        $date = $startDate->copy();

        while ($date->lte($endDate)) {
            // Skip Saturday(6) and Sunday(7) based on ISO week
            if (!in_array($date->dayOfWeekIso, [6, 7])) {
                foreach ($teachers as $teacher_id => $slotTime) {
                    $slotStart = Carbon::parse(
                        $date->toDateString() . ' ' . $slotTime,
                        'Asia/Yangon'
                    );

                    // Random check-in delay (0–10 mins late)
                    $checkIn = $slotStart->copy()->addMinutes(rand(0, 10));

                    // Random checkout type
                    $checkoutType = $checkoutTypes[array_rand($checkoutTypes)];

                    if ($checkoutType === 'auto') {
                        $checkOut = $checkIn->copy()->addMinutes(30);
                    } else {
                        $checkOut = $checkIn->copy()->addMinutes(rand(30, 59));
                    }

                    // Ensure checkout never goes past 12:00
                    $latest = $date->copy()->setTime(11, 59, 59);
                    if ($checkOut->gt($latest)) {
                        $checkOut = $latest->copy();
                    }

                    DB::table('teachers')->insert([
                        'teacher_id'    => $teacher_id,
                        'check_in'      => $checkIn->toDateTimeString(),
                        'check_out'     => $checkOut->toDateTimeString(),
                        'checkout_type' => $checkoutType,
                        'created_at'    => $checkIn->toDateTimeString(),
                        'updated_at'    => $checkOut->toDateTimeString(),
                    ]);
                }
            }

            $date->addDay();
        }
    }
}