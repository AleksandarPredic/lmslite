<?php

namespace App\Models;

use App\Models\Traits\ResourceScopeFilterSearchByName;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    use ResourceScopeFilterSearchByName;

    protected $fillable = ['name'];

    public function scopeOrderByName(Builder $query): Collection
    {
        return $query->orderBy('name')->get();
    }
}
