<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait ModelEnumTrait
{
    /**
     * Get enum DB table column values
     *
     * @param string $column DB table column name
     * @param int $cacheTTL TTL in seconds
     *
     * @return array
     * @throws \Exception
     */
    protected static function getEnumValues(string $column, int $cacheTTL)
    {
        return cache()->remember(
            'getEnumColumnDBValues.enum.' . $column,
            now()->addSeconds($cacheTTL),
            function () use ($column) {
                $table = Str::snake(Str::pluralStudly(class_basename(__CLASS__)));
                $type = DB::select( DB::raw("SHOW COLUMNS FROM {$table} WHERE Field = '{$column}'") )[0]->Type;

                preg_match('/^enum\((.*)\)$/', $type, $matches);

                $enum = array();
                foreach( explode(',', $matches[1]) as $value )
                {
                    $enum[] = trim( $value, "'" );
                }

                return $enum;
            }
        );
    }
}
