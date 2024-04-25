<?php

namespace Tests\Feature\Structure;

class LookupCategories
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
        ];
    }
}
