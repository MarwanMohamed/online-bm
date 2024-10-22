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
        Schema::rename('adm_users', 'users');
        Schema::rename('adm_activity_logs', 'activity_logs');
        Schema::rename('adm_roles', 'old_roles');
        Schema::rename('dat_area', 'areas');
        Schema::rename('adm_settings', 'settings');
        Schema::rename('ins_discounts', 'discounts');
        Schema::rename('ins_comprehensive', 'comprehensive');
        Schema::rename('ins_companies', 'companies');
        Schema::rename('ins_status', 'statuses');
        Schema::rename('ins_thirdparty', 'thirdparty');
        Schema::rename('tbl_blacklist', 'blacklists');
        Schema::rename('tbl_customers', 'customers');
        Schema::rename('tbl_insurance', 'insurances');
        Schema::rename('vhl_class', 'vehicle_models');
        Schema::rename('vhl_colors', 'vehicle_colors');
        Schema::rename('vhl_make', 'vehicles');
        Schema::rename('tbl_quickpay', 'quickpay');
        Schema::rename('tbl_transaction', 'transactions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
