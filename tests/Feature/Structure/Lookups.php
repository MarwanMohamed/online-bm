<?php

namespace Tests\Feature\Structure;

class Lookups
{
    /**
     * The json structure for the lookup categories response.
     *
     * @return string[]
     */
    public static function structure(): array
    {
        return [
            'id',
            'name',
            'code',
            'value',
            'model_type',
            'category_id',
            'is_active',
            'is_system',
            'created_at' => ['date', 'datetime', 'for_humans', 'formatted'],
        ];
    }
}
