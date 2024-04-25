<?php

namespace App\Console\Commands\Generators;

use Illuminate\Support\Str;
use App\Console\Commands\CrudMakeCommand;
use Stichoza\GoogleTranslate\GoogleTranslate;

class Migration extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command)
    {
        $name = Str::of($command->argument('name'))->singular()->studly();

        $table = Str::of($name)->snake()->lower()->plural();

        if (count(glob(database_path("migrations/*_create_{$table}_table.php")))) {
            return;
        }

        static::put(
            database_path("migrations"),
            date('Y_m_d_His')."_create_{$table}_table.php",
            self::qualifyContent(__DIR__.'/stubs/migration.stub', $name)
        );
    }
}
