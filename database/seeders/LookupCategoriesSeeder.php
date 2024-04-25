<?php
namespace Database\Seeders;

use App\Models\Lookup\LookupCategory;
use Illuminate\Database\Seeder;

class LookupCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $data = [
             [
                 'name' => 'Customer Types',
                 'code' => 'customer_types'
             ],
             [
                 'name' => 'Contract Statuses',
                 'code' => 'contract_statuses'
             ],
             [
                 'name' => 'Permission Categories',
                 'code' => 'permission_categories'
             ],
         ];

         foreach ($data as $item) {
             LookupCategory::query()->updateOrCreate(
                 ['code' => $item['code']],
                 ['name' => $item['name']]);
         }
    }
}
