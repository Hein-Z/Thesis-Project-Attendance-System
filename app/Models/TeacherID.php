<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;

class TeacherID extends Model
{
   

    protected $table = 'teacher_id';
    protected $primaryKey = 'id';
    public $incrementing = false;   // Because it’s a string primary key
    protected $keyType = 'string';
    protected $fillable = ['name', 'subject', 'present_times', 'absent_times'];
    public function attendances()
    {
        return $this->hasMany(Teacher::class, 'teacher_id', 'id');
    }
}
