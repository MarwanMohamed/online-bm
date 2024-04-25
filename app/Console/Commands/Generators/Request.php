<?php

namespace App\Console\Commands\Generators;

use Illuminate\Support\Str;
use App\Console\Commands\CrudMakeCommand;

class Request extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command)
    {
        $name = Str::of($command->argument('name'))->singular()->studly();

        static::put(
            app_path("Http/Requests/{$name}"),
            'Store'.$name.'Request.php',
            self::qualifyContent(__DIR__.'/stubs/Requests/StoreRequest.stub', $name)
        );

        static::put(
            app_path("Http/Requests/{$name}"),
            'Update'.$name.'Request.php',
            self::qualifyContent(__DIR__.'/stubs/Requests/UpdateRequest.stub', $name)
        );
    }
}
