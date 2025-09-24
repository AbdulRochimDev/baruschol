<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['user_id','nis','nisn','homeroom_class_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function homeroomClass()
    {
        return $this->belongsTo(\App\Models\SchoolClass::class, 'homeroom_class_id');
    }
}
