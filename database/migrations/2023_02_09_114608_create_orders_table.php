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
        Schema::create('orders', function (Blueprint $table) {
          $table->id();
          $table->date('date_of_issue');
          $table->string('phone_number', 12);
          $table->string('name');
          $table->text('desc')->nullable();
          $table->char('complexity', 10);   # From Complexity table
          $table->string('color_fabric');
          $table->string('backdrop');
          $table->tinyInteger('quantity');
          $table->string('size');
          $table->string('prepayment');
          $table->string('price');
          $table->unsignedBigInteger('user_id');
          $table->string('delivery');
          $table->unsignedBigInteger('status_id');
          $table->string('media')->nullable();
          $table->timestamps();

          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
