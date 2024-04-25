<?php

namespace Tests\Feature\Structure;

class Users
{
    /**
     * The json structure for the user response.
     *
     * @return string[]
     */
    public static function structure(): array
    {
        return [
            'id',
            'name',
            'email',
            'profile_picture',
            'is_active',
            'created_at' => ['date', 'datetime', 'for_humans', 'formatted'],
        ];
    }
}
