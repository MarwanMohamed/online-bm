<?php

namespace App\Enums;

use ArchTech\Enums\Values;
use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Names;

enum SocialProvider: string
{
    use Values;
    use InvokableCases;
    use Names;

    case GOOGLE = 'GOOGLE';
    case META = 'META';

    public function getDisplayName()
    {
        return match ($this) {
            SocialProvider::GOOGLE => 'Google',
            SocialProvider::META => 'Meta',
        };
    }

    public function getOrder()
    {
        return match ($this) {
            SocialProvider::GOOGLE => 1,
            SocialProvider::META => 2,
        };
    }

    public function useForLogin()
    {
        return match ($this) {
            SocialProvider::GOOGLE => true,
            SocialProvider::META => true,
        };
    }

    public function getServiceName()
    {
        return match ($this) {
            SocialProvider::GOOGLE => 'google',
            SocialProvider::META => 'facebook',
        };
    }
}
