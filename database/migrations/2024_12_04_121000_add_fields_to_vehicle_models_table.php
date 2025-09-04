<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vehicle_models', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicle_models', 'make_id')) {
                $table->unsignedBigInteger('make_id')->nullable()->after('model_name');
            }
            if (!Schema::hasColumn('vehicle_models', 'active')) {
                $table->boolean('active')->default(1)->after('make_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_models', function (Blueprint $table) {
            if (Schema::hasColumn('vehicle_models', 'make_id')) {
                $table->dropColumn('make_id');
            }
            if (Schema::hasColumn('vehicle_models', 'active')) {
                $table->dropColumn('active');
            }
        });
    }
};
