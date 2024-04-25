<?php

namespace App\Home\Transformers;

use Carbon\Carbon;

class DateFormatter
{
    /**
     * Format the given date.
     */
    public static function make(?Carbon $date): ?array
    {
        if (! $date) {
            return null;
        }

        return [
            'date' => $date->format('d-m-Y'),
            'datetime' => $date->format('d-m-Y h:i:s A'),
            'for_humans' => $date->diffForHumans(),
            'formatted' => $date->toDayDateTimeString(),
        ];
    }
}
