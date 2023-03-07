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
          $table->string('phone_number', 15);
          $table->string('client_name');
          $table->text('desc')->nullable();
          $table->char('complexity', 50);   # From Complexity table
          $table->string('color_fabric');
          $table->string('backdrop');
          $table->unsignedSmallInteger('quantity');
          $table->string('size');
          $table->unsignedInteger('prepayment_cur_id');
          $table->decimal('prepayment', $precision = 10, $scale = 2);
          $table->unsignedInteger('price_cur_id');
          $table->decimal('price', $precision = 10, $scale = 2);
          $table->unsignedBigInteger('user_id')->nullable();
          $table->string('delivery');
          $table->unsignedInteger('status_id');
          $table->string('telegram_link')->nullable();
          $table->string('photos')->nullable();
          $table->boolean('archived')->default(false);
          $table->timestamps();

          $table->foreign('prepayment_cur_id')->references('id')->on('currencies')->onDelete('cascade');
          $table->foreign('price_cur_id')->references('id')->on('currencies')->onDelete('cascade');
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
