<?php

namespace Database\Seeders;

use App\Models\Lookup\Lookup;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LookupsSeeder::class);

        $permissions = [
            [
                'name' => 'add_user',
                'display_name' => 'Add User',
                'category' => 'users_module',
            ],
            [
                'name' => 'view_user',
                'display_name' => 'View User',
                'category' => 'users_module',
            ],
            [
                'name' => 'edit_user',
                'display_name' => 'Edit User',
                'category' => 'users_module',
                "mandatory_permissions"=> ["view_user" ]
            ],
            [
                'name' => 'delete_user',
                'display_name' => 'Delete User',
                'category' => 'users_module',
                "mandatory_permissions"=> ["view_user" ]
            ],
            [
                'name' => 'active_user',
                'display_name' => 'Active User',
                'category' => 'users_module',
                "mandatory_permissions"=> ["view_user" ]
            ],
        ];

        foreach ($permissions as $permission) {
            $category = Lookup::query()
                ->whereRelation('category', 'code', 'permission_categories')
                ->firstWhere('code', $permission['category']);

            Permission::query()->updateOrCreate([
                'name' => $permission['name'],
                'category_id' => $category->id,
            ], [
                'name' => $permission['name'],
                'display_name' => $permission['display_name'],
                'category_id' => $category->id,
                "mandatory_permissions"=> $permission['mandatory_permissions']??null
            ]);
        }

        if ($role = Role::query()->firstWhere('name', 'admin')) {
            $role->permissions()->sync(Permission::all());
        }
    }
}
