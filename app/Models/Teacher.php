<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TeacherID;

class Teacher extends Model
{
    protected $table = 'teachers';
    protected $fillable = ['teacher_id','check_in','checkout_type', 'check_out'];

    public function teacher_info()
{
    return $this->belongsTo(TeacherID::class, 'teacher_id', 'id');
}
public function getCreatedAtMyanmarAttribute()
{
    return $this->created_at->timezone('Asia/Yangon')->format('d-m-Y H:i');
}
}
