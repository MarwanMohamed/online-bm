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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->renameColumn('vehicle_make', 'name');
            $table->renameColumn('created', 'created_at');
            $table->renameColumn('updated', 'updated_at');
        });

        Schema::table('vehicle_colors', function (Blueprint $table) {
            $table->renameColumn('color', 'name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
