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

if (! function_exists('lmsCarbonDateFormat')) {
    /**
     * Used across the application to unify the date displayed in common format.
     * Mostly used Models that doesn't require visible time, just date.
     *
     * @param Carbon $dateTime
     *
     * @return string
     */
    function lmsCarbonDateFormat(Carbon $dateTime): string
    {
        return $dateTime->format(config('app.datetime_format.date_only'));
    }
}

if (! function_exists('lmsPricePublicFormat')) {
    /**
     * Used across the application to unify the price displayed in common format.
     * Shows decimal places only when they exist.
     *
     * @param float|string|null $price
     *
     * @return string
     */
    function lmsPricePublicFormat($price): string
    {
        if ($price === null) {
            return _('Empty');
        }

        // Convert to float to ensure proper comparison
        $price = (float) $price;

        // Check if the price is a whole number
        $decimals = (floor($price) == $price) ? 0 : 2;

        return number_format($price, $decimals);
    }
}
