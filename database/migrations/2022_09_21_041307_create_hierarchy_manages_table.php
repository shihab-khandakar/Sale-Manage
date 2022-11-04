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
        Schema::create('hierarchy_manages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(2);
            $table->unsignedBigInteger('hierarchy_id')->default(1);
            $table->unsignedBigInteger('nsm')->default(2);
            $table->unsignedBigInteger('admin')->nullable();
            $table->unsignedBigInteger('account')->nullable();
            $table->unsignedBigInteger('rm')->nullable();
            $table->unsignedBigInteger('asm')->nullable();
            $table->unsignedBigInteger('aso')->nullable();
            $table->unsignedBigInteger('dealer')->nullable();
            $table->unsignedBigInteger('sub-dealer')->nullable();
            $table->unsignedBigInteger('sr')->nullable();
            $table->unsignedBigInteger('retailer')->nullable();
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
        Schema::dropIfExists('hierarchy_manages');
    }
};
