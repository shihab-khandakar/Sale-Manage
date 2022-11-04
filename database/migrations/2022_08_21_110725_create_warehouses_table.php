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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string("name", 100)->unique();
            $table->string("state", 200)->unique()->nullable();
            $table->string("location_url")->unique()->nullable();
            $table->string("code")->unique()->nullable();
            $table->unsignedBigInteger("upazila_id")->unique();
            $table->unsignedBigInteger("district_id")->unique();
            $table->unsignedBigInteger("division_id");
            $table->tinyInteger("status")->default(true);
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
        Schema::dropIfExists('warehouses');
    }
};
