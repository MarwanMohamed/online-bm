<?php

namespace Database\Seeders;

use App\Enums\Feature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use YlsIdeas\FeatureFlags\Facades\Features;

class FeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $features = Feature::names();

        $table = config('features.gateways.database.table');

        foreach ($features as $feature) {
            DB::table($table)->updateOrInsert([
                'feature' => $feature,
            ], [
                'title' => $feature,
            ]);
        }

        $onFeatures = [
            Feature::FORGET_PASSWORD->value,
            Feature::CHANGE_PASSWORD->value,
            Feature::LOGIN->value,
            Feature::LOOKUPS->value,
            Feature::LOOKUP_CATEGORIES->value,
            Feature::ROLES->value,
            Feature::PERMISSIONS->value,
            Feature::UPDATE_PROFILE->value,
            Feature::TOGGLE_ACTIVE->value,
            Feature::USER_CRUD_OPERATIONS->value,
            Feature::ENFORCEMENT_CHANGE_PASSWORD->value,
        ];

        foreach ($onFeatures as $feature) {
            Features::turnOn('database', $feature);
        }
    }
}
