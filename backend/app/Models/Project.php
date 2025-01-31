<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'description',
        'status',
        'start_date',
        'end_date',
        'budget',
    ];

    public function faculty(){
        return $this->belongsToMany(Faculty::class,'faculty_project','project_id','faculty_id');
    }
}
