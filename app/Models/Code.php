<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'edited_by'];

    public function getCode()
    {
        return $this->code;
    }

    public function getEditor()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }
}
