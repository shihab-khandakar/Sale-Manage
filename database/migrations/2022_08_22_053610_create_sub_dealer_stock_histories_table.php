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
        Schema::create('sub_dealer_stock_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("sub_dealer_stock_id");
            $table->string("date_time");

            // $table->integer("quantity_box")->nullable();
            $table->integer("quantity_pisces")->nullable();
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
        Schema::dropIfExists('sub_dealer_stock_histories');
    }
};
