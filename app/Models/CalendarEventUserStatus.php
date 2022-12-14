<?php

namespace App\Models;

use App\Http\Controllers\Traits\ModelEnumTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CalendarEventUserStatus extends Model
{
    use HasFactory, ModelEnumTrait;

    protected const CACHE_TTL = 86400;

    protected $fillable = ['calendar_event_id', 'user_id', 'status', 'info'];

    public function calendarEvent(): BelongsTo
    {
        return $this->belongsTo(CalendarEvent::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
