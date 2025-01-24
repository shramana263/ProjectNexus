<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'contact_no',
        'role',
        'password',
        'otp',
        'college_id',
    ];
}
