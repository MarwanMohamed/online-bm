<?php

namespace Database\Seeders;

use App\Models\Lookup\Lookup;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use App\Models\Lookup\LookupCategory;

class LookupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LookupCategoriesSeeder::class);

        if ($category = LookupCategory::where('code', 'customer_types')->first()) {
            $lookups = [
                [
                    'code' => 'private',
                    'name' => 'Private',
                    'value' => '1',
                    'model_type' => 'App\Models\Customer\Customer',
                    'category_id' => $category->id,
                ],
                [
                    'code' => 'corporate',
                    'name' => 'Corporate',
                    'value' => '2',
                    'model_type' => 'App\Models\Customer\Customer',
                    'category_id' => $category->id,
                ],
            ];
            foreach ($lookups as $lookup) {
                Lookup::query()->updateOrCreate(
                    [
                        'code' => $lookup['code'],
                        'category_id' => $lookup['category_id'],
                    ],
                    [
                        'name' => $lookup['name'],
                        'model_type' => $lookup['model_type'],
                        'value' => $lookup['value'],
                    ]
                );
            }
        }

        if ($category = LookupCategory::where('code', 'permission_categories')->first()) {
            $lookups = [
                [
                    'code' => 'users_module',
                    'name' => 'Users',
                    'model_type' => Permission::class,
                    'category_id' => $category->id,
                ],
                [
                    'code' => 'roles_module',
                    'name' => 'Roles & Permissions',
                    'model_type' => Permission::class,
                    'category_id' => $category->id,
                ],
            ];
            foreach ($lookups as $lookup) {
                Lookup::query()->updateOrCreate(
                    [
                        'code' => $lookup['code'],
                        'category_id' => $lookup['category_id'],
                    ],
                    [
                        'name' => $lookup['name'],
                        'model_type' => $lookup['model_type'],
                    ]
                );
            }
        }
    }
}
