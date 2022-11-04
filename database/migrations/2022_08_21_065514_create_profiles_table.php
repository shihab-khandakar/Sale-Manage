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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string("f_name", 20)->default("Tayler");
            $table->string("l_name", 20)->default("Archer");
            // $table->string("image", 20)->default("default_profile.png");

            $table->string("state", 50)->nullable();
            $table->unsignedBigInteger("upazila_id");
            $table->unsignedBigInteger("district_id");
            $table->unsignedBigInteger("division_id");

            $table->string("nid")->unique()->nullable();
            $table->string("birth_no")->unique()->nullable();
            // $table->string("nid_font")->nullable();
            // $table->string("nid_back")->nullable();
            $table->string("birth")->nullable();
            // $table->string("father_nid_font")->nullable();
            // $table->string("father_nid_back")->nullable();

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
        Schema::dropIfExists('profiles');
    }
};
