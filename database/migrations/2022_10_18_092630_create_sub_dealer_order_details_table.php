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
        Schema::create('sub_dealer_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("sub_dealer_order_id");
            $table->unsignedBigInteger("product_id")->comment("any gift item set product table");
            $table->unsignedBigInteger("category_id");
            $table->unsignedBigInteger("brand_id");
            
            $table->integer("quantity");
            $table->double("sub_total", 10, 2)->default(00.00);
            $table->integer("gift_item")->default(0)->comment("it update should be ware house manager");
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
        Schema::dropIfExists('sub_dealer_order_details');
    }
};
