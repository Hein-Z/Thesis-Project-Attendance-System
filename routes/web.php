<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherIDController;
use App\Http\Controllers\StudentIDController;
use App\Http\Controllers\StudentAttendanceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/new', function () {
    return view('new');
});

Route::get('/table', function () {
    return view('table');
});


Route::get('/students',
    [StudentController::class, 'show']
);

Route::get('/{teacher_id}/teacher', [TeacherIDController::class, 'storeById']);
Route::get('/{student_id}/student', [StudentIDController::class, 'storeById']);

Route::prefix('teachers')->name('teachers.')->group(function () {
    Route::get('/', [TeacherController::class, 'show'])->name('index');
    Route::get('/{teacher}/edit', [TeacherController::class, 'edit'])->name('edit');
    Route::put('/{id}', [TeacherController::class, 'update'])->name('update');
    Route::delete('/{teacher}', [TeacherController::class, 'destroy'])->name('destroy');
Route::get('/{teacher}/profile', [TeacherController::class, 'profile'])->name('profile');
    Route::get('{teacher}/attendance-data', [TeacherController::class, 'attendanceData'])->name('attendance.data');

});
Route::get('/latest-teacher-attendance', [TeacherController::class, 'latest']);


use Carbon\Carbon;

Route::get('/time', function () {
    // Get Myanmar current time in 24-hour format
    $time = Carbon::now('Asia/Yangon')->format('H:i');

    return response()->json([
        'time' => $time
    ]);
});

Route::get('/attendance/mark-absent', [StudentAttendanceController::class, 'markAbsent']);

Route::get('/{student_id}/student', [StudentAttendanceController::class, 'markAttendance']);


Route::get('/students/{id}', [StudentController::class, 'profile'])->name('students.profile');