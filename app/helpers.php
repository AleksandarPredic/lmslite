<?php

use Carbon\Carbon;

if (! function_exists('lmsCarbonPublicFormat')) {
    /**
     * @param Carbon $dateTime
     *
     * @return string
     */
    function lmsCarbonPublicFormat(Carbon $dateTime): string
    {
        return $dateTime->format(config('app.datetime_format.public'));
    }
}
