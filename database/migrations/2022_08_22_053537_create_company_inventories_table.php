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
        Schema::create('company_inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('product_name')->nullable();
            $table->string('category_name')->nullable();
            $table->string('brand_name')->nullable();
            $table->integer('old_quantity')->nullable();
            $table->integer('add_or_less')->nullable();
            $table->integer('now_quantity')->nullable();
            $table->string('type')->nullable();
            $table->double('price')->nullable();
            $table->double('amount')->nullable();
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
        Schema::dropIfExists('company_inventories');
    }
};
