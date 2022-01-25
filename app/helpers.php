<?php

use Carbon\Carbon;

if (! function_exists('lmsCarbonPublicFormat')) {
    /**
     * Used across the application to unify the date displayed in common format.
     * Mostly used for Event starting and ending time and similar Models.
     *
     * @param Carbon $dateTime
     *
     * @return string
     */
    function lmsCarbonPublicFormat(Carbon $dateTime): string
    {
        return $dateTime->format(config('app.datetime_format.public'));
    }
}

if (! function_exists('lmsCarbonDefaultFormat')) {
    /**
     * Used across the application to unify the date displayed in common format.
     * Mostly used Models that doesn't require visible week day name.
     *
     * @param Carbon $dateTime
     *
     * @return string
     */
    function lmsCarbonDefaultFormat(Carbon $dateTime): string
    {
        return $dateTime->format(config('app.datetime_format.default'));
    }
}
