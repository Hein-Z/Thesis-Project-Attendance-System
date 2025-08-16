<?php

namespace App\Models;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;

class StudentID extends Model
{
    protected $table = 'student_ids';
    protected $primaryKey = 'student_id';  // use student_id instead of id
    public $incrementing = false;          // student_id is not auto-incrementing
    protected $keyType = 'string'; 
    protected $fillable = ['student_id', 'name'];
  

    // One student has many attendances
    public function attendances()
    {
        return $this->hasMany(Student::class, 'student_id');
    }
}
