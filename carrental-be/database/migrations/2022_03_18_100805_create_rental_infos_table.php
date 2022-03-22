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
        Schema::create('rental_infos', function (Blueprint $table) {
            $table->id();
            $table->dateTime('pickup_date');
            $table->dateTime('return_date');
            $table->text('additional_instruction')->nullable();
            $table->string('payment_status');
            $table->string('with_driver');
            $table->foreignId('driver_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('car_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('payment_type');
            $table->string('total_payment');
            $table->integer('days_with_driver')->nullable();
            $table->string('driver_payment')->nullable();
            $table->text('drivers_license')->nullable();
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
        Schema::dropIfExists('rental_infos');
    }
};
