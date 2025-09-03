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
        Schema::table('insurances', function (Blueprint $table) {
            if (!Schema::hasColumn('insurances', 'vhl_body_type')) {
                $table->string('vhl_body_type')->nullable()->after('vhl_color');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insurances', function (Blueprint $table) {
            if (Schema::hasColumn('insurances', 'vhl_body_type')) {
                $table->dropColumn('vhl_body_type');
            }
        });
    }
};
