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
        Schema::create('varients', function (Blueprint $table) {
            $table->id();
            $table->string("sku");
            $table->string("v_name");
            $table->string("unit");
            $table->string("qty");
            $table->double("price", 12, 2);
            $table->double("sales_price", 12, 2);
            $table->double("weight", 12, 2);
            $table->string("status");
            $table->string("image_path");
            $table->string("pro_id");
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
        Schema::dropIfExists('varients');
    }
};
