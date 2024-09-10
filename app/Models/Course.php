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

    /**
     * @param string $name
     * @param double $price
     *
     * @return CourseDiscount
     */
    public function addNewDiscount($name, $price)
    {
        return $this->courseDiscounts()->create([
            'name' => $name,
            'price' => $price
        ]);
    }

    /**
     *
     * @param double $price
     *
     * @return CourseMembership
     */
    public function addNewMembershipPrice($price)
    {
        return $this->courseMembershipPrices()->create([
            'price' => $price
        ]);
    }

    /**
     * Used to get the value for the edit screen input
     *
     * @return float
     */
    public function getLatestMembershipPriceAsDecimal()
    {
        return $this->getLatestMembershipPrice()->pluck('price')->first();
    }

    public function getLatestMembershipPrice()
    {
        return $this->courseMembershipPrices()->latest();
    }

    public function getAllPricesSordedFromNewest()
    {
        return $this->courseMembershipPrices()->orderBy('created_at', 'desc')->get();
    }

    public function getAllPricesSordedFromOldest()
    {
        return $this->courseMembershipPrices()->orderBy('created_at', 'asc')->get();
    }

    public function courseMembershipPrices(): HasMany
    {
        return $this->hasMany(CourseMembership::class);
    }

    public function courseDiscounts(): HasMany
    {
        return $this->hasMany(CourseDiscount::class);
    }
}
