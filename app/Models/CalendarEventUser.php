<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEventUser extends Model
{
    use HasFactory;

    protected $fillable = ['calendar_event_id', 'user_id'];

    /**
     * The attributes that should be cast.
     * https://laravel.com/docs/8.x/eloquent-mutators#attribute-casting
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
