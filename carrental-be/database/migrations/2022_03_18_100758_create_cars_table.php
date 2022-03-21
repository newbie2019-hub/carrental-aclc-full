<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->text('image')->nullable();
            // $table->integer('quantity')->default(0);
            $table->string('fuel_type');
            $table->string('plate_number');
            $table->string('vehicle_identification_number');
            $table->text('description')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('seats');
            $table->string('mileage');
            $table->string('model');
            $table->string('year');
            $table->string('transmission');
            $table->string('for_rent_status')->default('Pending');
            $table->foreignId('car_rate_id')->constrained()->onDelete('cascade');
            $table->foreignId('car_brand_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('cars');
    }
};
