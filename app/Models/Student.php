<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'info', 'student_class_id', 'active', 'minutes_open'];

    public function class()
    {
        return $this->belongsTo(StudentClass::class, 'student_class_id');
    }

    public function clocks()
    {
        return $this->hasMany(Clocks::class,'student_id');
    }

    public function getClockByDate($date)
    {
        return $this->clocks()->where('date', $date)->first();
    }
}
