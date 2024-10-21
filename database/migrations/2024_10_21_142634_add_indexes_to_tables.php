<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('insurances', function (Blueprint $table) {
            $table->index('policy_id');
            $table->index('name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('name');
        });

        Schema::table('quickpay', function (Blueprint $table) {
            $table->index('ref_no');
            $table->index('name');
            $table->index('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
