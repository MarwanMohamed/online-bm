<?php

namespace App\Console\Commands\Generators;

use Illuminate\Support\Str;
use App\Console\Commands\CrudMakeCommand;

class Resource extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command)
    {
        $name = Str::of($command->argument('name'))->singular()->studly();

        static::put(
            app_path("Http/Resources"),
            $name.'Resource.php',
            self::qualifyContent(__DIR__.'/stubs/Resource.stub', $name)
        );
    }
}
