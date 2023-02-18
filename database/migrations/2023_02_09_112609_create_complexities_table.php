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
        Schema::create('complexities', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->char('char', 10)->unique();
            $table->unsignedInteger('currency_id');
            $table->unsignedSmallInteger('complexity_order');
            $table->decimal('value', $precision = 10, $scale = 2);
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
        Schema::dropIfExists('complexities');
    }
};
