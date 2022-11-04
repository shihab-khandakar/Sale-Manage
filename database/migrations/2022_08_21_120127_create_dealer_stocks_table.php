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
        Schema::create('dealer_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("dealer_id");
            $table->string("d_code");
            $table->string("date");

            $table->unsignedBigInteger("product_id");
            $table->unsignedBigInteger("category_id");
            $table->unsignedBigInteger("brand_id");
            // $table->unsignedBigInteger("attribute_id");
            $table->unsignedBigInteger("stock_by");

            // $table->integer("quantity_box")->nullable();
            $table->integer("quantity_pisces")->nullable();
            // $table->integer("booking_quantity_box")->nullable();
            $table->integer("booking_quantity_pisces")->nullable();

            $table->string("type", 10)->default("new")->comment("is field contain 3 type 1, new 2, return, 3, damage");
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
        Schema::dropIfExists('dealer_stocks');
    }
};
