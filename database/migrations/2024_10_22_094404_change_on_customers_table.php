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
        Schema::table('customers', function (Blueprint $table) {
            $table->renameColumn('created', 'created_at');
            $table->renameColumn('updated', 'updated_at');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('role', 'role_id');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->integer('user')->index()->change();
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->renameColumn('user', 'user_id');
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
