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
        Schema::create('sub_dealer_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("sub_dealer_id");
            $table->string("approval_id")->nullable();
            $table->tinyInteger("is_approve")->default(false);
            $table->unsignedBigInteger("offer_id")->nullable();
            $table->unsignedBigInteger("side_party_information_id")->nullable();
            $table->unsignedBigInteger("transport_id")->nullable();
            $table->tinyInteger("direct_receive")->default(false)->comment("if checked is contain true");

            $table->string("d_code");
            $table->string("order_code")->comment("dealer-date-this_dealer_order_serial");
            $table->string("date");
            $table->string("payment_status")->default(false);
            $table->string("order_status")->default("seller approve")->comment("it always change able, example admin approve/ cancel");

            $table->string("commission_type")->default("percentage");
            $table->double("commission_value", 10, 2)->nullable();
            $table->double("commission_amount", 10, 2)->nullable();
            $table->double("without_commission_bill", 10, 2)->default(00.00);
            $table->double("total_bill", 10, 2)->default(00.00);

            $table->double("provided_amount", 10, 2)->default(00.00);
            $table->double("providable_amount", 10, 2)->default(00.00);

            $table->tinyInteger("distribute")->default(false)->comment("all product distribute complete then update false to true");
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
        Schema::dropIfExists('sub_dealer_orders');
    }
};
