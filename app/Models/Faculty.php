<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'is_collaborating',
        'department'
    ];

    public function skill(){
        return $this->belongsToMany(Skill::class,'faculty_skill','faculty_id','skill_id');
    }

    public function project(){
        return $this->belongsToMany(Project::class,'faculty_project','faculty_id','project_id');
    }
}
