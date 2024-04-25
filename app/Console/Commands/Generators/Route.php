<?php

namespace App\Console\Commands\Generators;

use Illuminate\Support\Str;
use App\Console\Commands\CrudMakeCommand;

class Route extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command)
    {
        $name = Str::of($command->argument('name'))->singular()->studly();

        static::put(
            base_path('routes/Api'),
            $name->snake()->lower()->plural().'.php',
            self::qualifyContent(__DIR__.'/stubs/route.stub', $name)
        );
    }
}
