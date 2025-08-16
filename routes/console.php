<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

use App\Console\Commands\MaskAbsentSubjects;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



// $app->afterBootstrapping(Illuminate\Foundation\Bootstrap\RegisterFacades::class, function ($app) {
//     $schedule = $app->make(Schedule::class);

//     // Schedule the command every 5 minutes
//     $schedule->command('attendance:mark-absent')->everyFiveMinutes();
// });

Schedule::command('attendance:mark-absent')->everyMinute();