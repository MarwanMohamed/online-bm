<?php

namespace App\Console\Commands;

use App\Models\Permission;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AddRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:add 
    {role_name : The name of the role.}
    {permissions? : The permission ids seperated by coma.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new role with permissions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (! $role = Role::firstWhere('name', $this->argument('role_name'))) {
            $role = Role::create([
                'name' => $this->argument('role_name'),
                'display_name' => $this->argument('role_name'),
            ]);
        }

        $permissions = Permission::find($this->permissions());

        $role->permissions()->sync($permissions);

        $this->line(sprintf(
            'Role "%s" was added with permissions %s',
            $role->name,
            $permissions->pluck('name')
        ));

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return Command::SUCCESS;
    }

    protected function permissions(): array
    {
        return explode(',', $this->argument('permissions'));
    }
}
