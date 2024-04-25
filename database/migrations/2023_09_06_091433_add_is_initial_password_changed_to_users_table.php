<?php

use App\Enums\Feature;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use YlsIdeas\FeatureFlags\Facades\Features;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_initial_password_changed')->default(0)->after('password');
        });

        $featuresTable = config('features.gateways.database.table');

        DB::table($featuresTable)->updateOrInsert([
            'feature' => $feature = Feature::ENFORCEMENT_CHANGE_PASSWORD->value,
        ], [
            'title' => $feature,
        ]);

        Features::turnOn('database', $feature);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $featuresTable = config('features.gateways.database.table');

        $feature = Feature::ENFORCEMENT_CHANGE_PASSWORD->value;

        DB::table($featuresTable)->where(compact('feature'))->delete();

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_initial_password_changed');
        });
    }
};
