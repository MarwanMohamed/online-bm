<?php

use App\Models\Thirdparty;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vehicle_models', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::create('vehicle_model_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('model_id');
            $table->year('year')->nullable();
            $table->text('details');
            $table->smallInteger('cylinder');
            $table->smallInteger('seats');
            $table->string('type');
            $table->integer('premium')->nullable();
            $table->timestamps();
        });

        Thirdparty::whereIn('id', [6, 7, 9, 22, 45])->update(['final' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_model_details');
    }
};
