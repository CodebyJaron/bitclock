<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clocks extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'date', 'inclock_time', 'outclock_time', 'excercise', 'status', 'note'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function getTimeBetweenInAndOut()
    {
        return $this->outclock_time - $this->inclock_time;
    }
}
