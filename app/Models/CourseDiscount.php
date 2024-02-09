<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDiscount extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'name', 'price'];

    protected $casts = [
        'price' => 'double',
    ];

    public function getPriceDisplayFormat()
    {
        return number_format($this->price, 2);
    }
}
