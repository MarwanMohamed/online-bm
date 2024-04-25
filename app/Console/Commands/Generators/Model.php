<?php

namespace App\Console\Commands\Generators;

use Illuminate\Support\Str;
use App\Console\Commands\CrudMakeCommand;

class Model extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command)
    {
        $name = Str::of($command->argument('name'))->singular()->studly();

        static::put(
            app_path("Models"),
            $name.'.php',
            self::qualifyContent(__DIR__.'/stubs/Model.stub', $name)
        );
    }
}
