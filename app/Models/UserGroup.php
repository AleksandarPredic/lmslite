<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserGroup extends Model
{
    use HasFactory;

    protected $fillable = ['group_id', 'user_id', 'price_type', 'inactive'];

    protected $casts = [
        'inactive' => 'boolean',
    ];
}
