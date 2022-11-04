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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('product name-attribute-litre or kg or gram');
            $table->bigInteger('brand_id')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->double('stock_in_price')->nullable();
            $table->double('stock_out_price')->nullable();
            $table->double('retailer_sale_price')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('offer_id')->nullable();
            $table->string('code')->nullable()->comment('name field');
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
        Schema::dropIfExists('products');
    }
};
