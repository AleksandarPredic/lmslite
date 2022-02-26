<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get all roles except admin role
     *
     * @param Builder $builder
     *
     * @return void
     */
    public function scopeExcludeAdminRole(Builder $builder)
    {
        $builder->where('id', '!=', 1);
    }
}
