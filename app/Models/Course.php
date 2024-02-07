<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function scopeOrderByName(Builder $query): Collection
    {
        return $query->orderBy('name')->get();
    }

    public function addNewMembershipPrice($price)
    {
        $this->courseMembershipPrices()->create([
            'price' => $price
        ]);
    }

    public function getLatestMembershipPriceAsDecimal()
    {
        return $this->getLatestMembershipPrice()->pluck('price')->first();
    }

    public function getLatestMembershipPrice()
    {
        return $this->courseMembershipPrices()->latest();
    }

    public function courseMembershipPrices(): HasMany
    {
        return $this->hasMany(CourseMembership::class);
    }
}
