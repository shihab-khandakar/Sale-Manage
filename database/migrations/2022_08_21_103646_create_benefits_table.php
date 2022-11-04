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

     

    // public function up()
    // {
        // this module work has stoped
    //     Schema::create('benefits', function (Blueprint $table) {
    //         $table->id();
    //         $table->unsignedBigInteger('user_id');
    //         $table->unsignedBigInteger('benefit_type_id');
    //         $table->double("benefit_amount", 10, 2)->default(00);
    //         $table->string("date")->nullable();
    //         $table->text("note")->nullable();
    //         $table->string('start_date')->nullable()->comment("if benefit type target then start date and end date assign");
    //         $table->string('end_date')->nullable()->comment("if benefit type target then start date and end date assign");
    //         $table->timestamps();
    //     });
    // }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('benefits');
    }
};
