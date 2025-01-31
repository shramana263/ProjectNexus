<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function faculty(){
        return $this->belongsToMany(Faculty::class,'faculty_skill','skill_id','faculty_id');
    }
}
