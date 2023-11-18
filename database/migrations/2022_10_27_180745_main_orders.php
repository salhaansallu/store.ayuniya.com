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
        Schema::create('main_orders', function (Blueprint $table) {
            $table->id();
            $table->string("order_number");
            $table->string("user_id");
            $table->string("bill_address");
            $table->string("ship_address");
            $table->string("status");
            $table->string("print")->default("");
            $table->double("delivery_charge", 12, 2);
            $table->double("total_order", 12, 2);
            $table->text("courier_name")->default("");
            $table->string("hand_over_date")->default("");
            $table->string("track_code")->default("");
            $table->string("track_link")->default("");
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
        Schema::dropIfExists('main_orders');
    }
};
