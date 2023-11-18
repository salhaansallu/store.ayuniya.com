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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string("company_name");
            $table->string("company_email");
            $table->string("company_number");
            $table->string("company_fax")->nullable();
            $table->string("company_address1")->nullable();
            $table->string("company_address2")->nullable();
            $table->string("company_website")->nullable();
            $table->string("business_type");
            $table->string("store_name");
            $table->string("license");
            $table->string("registration");
            $table->string("nop");
            $table->string("payment_type");
            $table->string("bank_name");
            $table->string("branch_name");
            $table->string("account_name");
            $table->string("account_number");
            $table->string("verify")->nullable();
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
        Schema::dropIfExists('vendors');
    }
};
