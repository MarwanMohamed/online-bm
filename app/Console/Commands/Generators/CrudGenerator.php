<?php

namespace App\Console\Commands\Generators;

use Illuminate\Support\Str;

class CrudGenerator
{
    protected static function qualifyContent($stub, $name)
    {
        return str_replace(
            [
                '{{studlySingular}}',
                '{{studlyPlural}}',
                '{{camelSingular}}',
                '{{camelPlural}}',
                '{{lowercaseSingular}}',
                '{{lowercasePlural}}',
                '{{lowercaseDisplayPlural}}',
                '{{lowercaseDisplaySingular}}',
                '{{uppercaseDisplayPlural}}',
                '{{uppercaseDisplaySingular}}',
            ],
            [
                $studlySingular = Str::of($name)->singular()->studly(), // UserProfile
                $studlyPlural = Str::of($name)->plural()->studly(),     // UserProfiles
                $camelSingular = Str::of($name)->singular()->camel(), // userProfile
                $camelPlural = Str::of($name)->plural()->camel(),     // userProfiles
                $lowercaseSingular = Str::of($name)->snake()->singular()->lower(),  //
                $lowercasePlural = Str::of($name)->snake()->plural()->lower(),
                $lowercaseDisplayPlural = Str::of($name)->snake()->replace('_', ' ')->plural()->lower(),
                $lowercaseDisplaySingular = Str::of($name)->snake()->replace('_', ' ')->singular()->lower(),
                $uppercaseDisplayPlural = Str::of($name)->snake()->replace('_', ' ')->ucfirst()->plural()->lower(),
                $uppercaseDisplaySingular = Str::of($name)->snake()->replace('_', ' ')->ucfirst()->singular()->lower(),
            ],
            file_get_contents($stub)
        );
    }

    protected static function put($path, $file, $content)
    {
        if (! is_dir($path)) {
            // dir doesn't exist, make it
            mkdir($path, 0777, true);
        }

        if (file_exists($path.'/'.$file)) {
            return;
        }

        file_put_contents($path.'/'.$file, $content);
    }
}
