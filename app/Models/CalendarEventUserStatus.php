<?php

namespace App\Models;

use App\Http\Controllers\Traits\ModelEnumTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEventUserStatus extends Model
{
    use HasFactory, ModelEnumTrait;

    protected const CACHE_TTL = 86400;

    protected $fillable = ['calendar_event_id', 'user_id', 'status', 'info'];

    public function calendarEvents()
    {
        return $this->hasMany(CalendarEvent::class, 'id', 'calendar_event_id');
    }

    public static function getStatuses(): array
    {
        $statuses = [];
        foreach (self::getStatusEnumValues() as $status) {
            $statuses[$status] = ucfirst(str_replace('-', ' ', $status));
        }

        return $statuses;
    }

    public static function getInfoOptions(): array
    {
        $options = [];
        foreach (self::getInfoEnumValues() as $info) {
            $options[$info] = ucfirst(str_replace('-', ' ', $info));
        }

        return $options;
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
            $values = self::getEnumValues('info', self::CACHE_TTL);
        } catch (\Exception $exception) {
            report($exception);
            return [];
        }

        return $values;
    }
}
