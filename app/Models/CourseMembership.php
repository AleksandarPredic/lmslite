<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseMembership extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'price'];

    protected $casts = [
        'price' => 'double',
        'starting_at' => 'datetime',
        'ending_at' => 'datetime',
    ];

    public function getPriceDisplayFormat()
    {
        return number_format($this->price, 2);
    }
}
