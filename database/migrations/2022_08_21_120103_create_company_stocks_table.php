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
        Schema::create('company_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("warehouse_id");
            $table->unsignedBigInteger("product_id");
            $table->unsignedBigInteger("category_id");
            $table->unsignedBigInteger("brand_id");
            $table->unsignedBigInteger("stock_by");
            $table->integer('quantity');
            $table->string('date');
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
        Schema::dropIfExists('company_stocks');
    }
};
