<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class faculty_skill extends Model
{
    use HasFactory;
    protected $fillable = [
        'faculty_id',
        'skill_id'
    ];
}
