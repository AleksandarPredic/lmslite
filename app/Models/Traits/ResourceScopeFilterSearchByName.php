<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ResourceScopeFilterSearchByName
{
    /**
     * Filter all active resources by name
     *
     * @param Builder $builder
     * @param string|null $name
     *
     * @return void
     */
    public function scopeFilterByName(Builder $builder, ?string $name)
    {
        $builder->when($name, function ($builder, $name) {
            $builder->whereRaw('lower(name) like (?)',["%{$name}%"]);
        });
    }
}
