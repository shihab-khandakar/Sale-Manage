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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("email", 40)->unique()->comment("email always unique");
            $table->string("name", 40);
            $table->string("shope_name");
            $table->string("phone", 11)->unique()->comment("phone always unique");
            $table->unsignedBigInteger("hierarchy_id")->comment("when assign any role");
            $table->unsignedBigInteger("role_id")->comment("when assign any role");
            $table->string("role")->comment("when assign any role, example: admin, sr, product creator, etc");
            $table->string("position",10)->comment("What kind of user are you trying to create?, example admin, sr, arm");
            $table->string("code")
            ->comment("when assing profile then auto create a code this format, hierarchy_id, division_id, destrict,
            upazila, and serial, example: 2-2-2-2-000001");
            $table->string("password");
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
        Schema::dropIfExists('users');
    }
};
