<?php

namespace App\Enums;

use ArchTech\Enums\Values;
use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Names;

enum UserAction: string
{
    use Values;
    use InvokableCases;
    use Names;

    case EMAIL_VERIFICATION = 'EMAIL_VERIFICATION';
    case CHANGE_EMAIL_REQUEST = 'CHANGE_EMAIL_REQUEST';

    public function getDisplayName()
    {
        return match ($this) {
            SELF::EMAIL_VERIFICATION => 'Email Verification',
            SELF::CHANGE_EMAIL_REQUEST => 'Change Email Request',
        };
    }

}
