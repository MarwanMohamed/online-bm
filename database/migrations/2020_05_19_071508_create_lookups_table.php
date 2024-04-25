<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLookupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lookups', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('value', 100)->default(null)->nullable();
            $table->string('model_type');
            $table->json('extra_details')->nullable()->default(null);            
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('lookup_categories')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_system')->default(false);
            $table->unique(['code', 'category_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lookups');
    }
}
