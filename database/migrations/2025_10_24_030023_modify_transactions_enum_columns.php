<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE transactions 
            MODIFY COLUMN txn_type ENUM('Debit', 'Credit', 'Other') 
            CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL");

        DB::statement("ALTER TABLE transactions 
            MODIFY COLUMN payment_gateway ENUM('DB', 'QCB', 'TESS') 
            CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
