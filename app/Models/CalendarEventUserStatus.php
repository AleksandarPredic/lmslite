<?php

namespace App\Models;

use App\Http\Controllers\Traits\ModelEnumTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEventUserStatus extends Model
{
    use HasFactory, ModelEnumTrait;

    protected const CACHE_TTL = 86400;

    protected $with = ['calendar_event_id', 'user_id', 'status', 'info'];

    public static function getStatuses()
    {
        // TODO: continue here to crete VO for status and for INFO and return it here, then use it in select
        // https://dev.to/bdelespierre/using-value-objects-in-laravel-models-44la
        $statuses = [];
        foreach (self::getStatusEnumValues() as $status) {

        }
    }

    public static function getStatusEnumValues(): array
    {
        try {
            $values = self::getEnumValues('status', self::CACHE_TTL);
        } catch (\Exception $exception) {
            report($exception);
            return [];
        }

        return $values;
    }

    public static function getInfoEnumValues(): array
    {
        try {
            $values = self::getEnumValues('status', self::CACHE_TTL);
        } catch (\Exception $exception) {
            report($exception);
            return [];
        }

        return $values;
    }
}
