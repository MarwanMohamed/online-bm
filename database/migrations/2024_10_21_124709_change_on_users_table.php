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
        Schema::table('users', function (Blueprint $table) {
           $table->string('password')->change();
        });

        Schema::table('blacklists', function (Blueprint $table) {
           $table->timestamps();
        });

         Schema::table('discounts', function (Blueprint $table) {
           $table->timestamps();
        });

         Schema::table('companies', function (Blueprint $table) {
           $table->timestamps();
        });

         Schema::table('settings', function (Blueprint $table) {
           $table->timestamp('updated_at')->nullable();
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
