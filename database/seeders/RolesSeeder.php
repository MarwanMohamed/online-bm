<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionsSeeder::class);

        $roles=[
            [
                "role"=>['name' => 'admin','display_name' => 'Admin',],
                "permissions"=>Permission::all()
            ],[
                "role"=>['name' => 'employee','display_name' => 'Employee',],
                "permissions"=>Permission::whereIn("name",["add_user","view_user"])->get(),
            ]
        ];


        foreach ($roles as $role){
            $dbRole = Role::updateOrCreate(['name' => $role['role']['name']], $role['role'] );
            $dbRole->permissions()->sync($role['permissions']);
        }

    }
}
