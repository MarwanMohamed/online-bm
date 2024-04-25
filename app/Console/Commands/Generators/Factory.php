<?php

namespace App\Console\Commands\Generators;

use Illuminate\Support\Str;
use App\Console\Commands\CrudMakeCommand;

class Factory extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command)
    {
        $name = Str::of($command->argument('name'))->singular()->studly();

        static::put(
            database_path("factories"),
            $name.'Factory.php',
            self::qualifyContent(__DIR__.'/stubs/Factory.stub', $name)
        );
    }
}
