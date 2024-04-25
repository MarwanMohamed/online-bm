<?php

namespace App\Console\Commands;

use App\Console\Commands\Generators\Controller;
use App\Console\Commands\Generators\Factory;
use App\Console\Commands\Generators\Migration;
use App\Console\Commands\Generators\Model;
use App\Console\Commands\Generators\Repository;
use App\Console\Commands\Generators\Service;
use App\Console\Commands\Generators\Request;
use App\Console\Commands\Generators\Resource;
use App\Console\Commands\Generators\Route;
use App\Console\Commands\Generators\Seeder;
use Illuminate\Console\Command;

class CrudMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud 
                            {name : Class (Singular), e.g user, place, car}
                            {--with-service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create all Crud operations with a single command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Controller::generate($this);
        Request::generate($this);
        Resource::generate($this);
        Model::generate($this);
        Factory::generate($this);
        Migration::generate($this);
        Seeder::generate($this);
        Route::generate($this);
        if ($this->option('with-service')) {
            Service::generate($this);
            Repository::generate($this);
        }
    }
}
