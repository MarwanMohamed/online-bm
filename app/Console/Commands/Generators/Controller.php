<?php

namespace App\Console\Commands\Generators;

use Illuminate\Support\Str;
use App\Console\Commands\CrudMakeCommand;

class Controller extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command)
    {
        $name = Str::of($command->argument('name'))->singular()->studly();  //Example: User, Post, UserProfile

        $withService = $command->option('with-service');

        $stub = $withService
            ? __DIR__.'/stubs/Controllers/ServiceController.stub'
            : __DIR__.'/stubs/Controllers/Controller.stub';

        static::put(
            app_path("Http/Controllers/Api/{$name->plural()}"),
            $name.'Controller.php',
            static::qualifyContent($stub, $name)
        );
    }
}
