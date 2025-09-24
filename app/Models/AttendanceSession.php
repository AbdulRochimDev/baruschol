<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    protected $table = 'attendance_sessions';

    protected $fillable = ['class_subject_id','status','date'];

    public function records()
    {
        return $this->hasMany(AttendanceRecord::class, 'attendance_session_id');
    }
}
