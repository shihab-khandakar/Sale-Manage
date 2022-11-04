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
        Schema::create('dealer_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("dealer_id");
            $table->string("approval_id")->nullable();
            $table->tinyInteger("is_approve")->default(false)->comment('it is true when admin approve');
            $table->string("order_status")->default("seller approve")->comment("it always change able, example admin approve/ cancel");
            $table->unsignedBigInteger("side_party_information_id")->nullable();
            $table->unsignedBigInteger("transport_id")->nullable();

            $table->tinyInteger("direct_receive")->default(false)->comment("if checked is contain true");
            $table->string("d_code");
            $table->string("order_code")->comment("dealer-date-this_dealer_order_serial");
            $table->string("date");
            $table->string("payment_status")->default(false);

            $table->double("total_bill", 10, 2)->default(00.00)->comment('total bill 200');
            $table->double("commission", 10, 2)->nullable()->comment('suppose user 2 product purches and per product price 100 taka, this user get commission 5%');
            $table->double("commission_amount", 10, 2)->nullable()->comment('user commission amount 200*5 = 1000/100 = 10');
            $table->double("net_bill", 10, 2)->default(00.00)->comment('net bill 190');

            $table->double("provided_amount", 10, 2)->default(00.00);
            $table->double("providable_amount", 10, 2)->default(00.00)->comment('provideable amount 190');

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
        Schema::dropIfExists('dealer_orders');
    }
};
