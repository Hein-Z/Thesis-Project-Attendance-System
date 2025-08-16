<?php

namespace App\Models;
use App\Models\StudentID;
use App\Models\TeacherID;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    protected $fillable = [
        'student_id', 'teacher_id', 'subject',
        'class_start', 'class_end', 'status', 'check_in', 'date'
    ];
    

    // Attendance belongs to a student
    public function student_info()
    {
        return $this->belongsTo(StudentID::class, 'student_id');
    }

    public function teacher_info()
    {
        return $this->belongsTo(TeacherID::class, 'teacher_id');
    }
}
