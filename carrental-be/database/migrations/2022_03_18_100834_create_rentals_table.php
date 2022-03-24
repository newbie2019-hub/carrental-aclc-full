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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->text('transaction_number');
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->foreignId('rentee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('rental_info_id')->constrained()->onDelete('cascade');
            $table->text('invoice');
            $table->string('status', 30);
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
        Schema::dropIfExists('rentals');
    }
};
