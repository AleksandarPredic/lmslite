<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseMembership extends Model
{
    protected $fillable = ['course_id', 'price'];

    protected $casts = [
        'price' => 'double',
    ];
}
