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
            $table->string("company_name")->default('');
            $table->string("company_email")->default('');
            $table->string("company_number")->default('');
            $table->string("company_fax")->default('');
            $table->string("company_address1")->default('');
            $table->string("company_address2")->default('');
            $table->string("company_website")->default('');
            $table->string("business_type")->default('');
            $table->string("store_name")->default('');
            $table->string("license")->default('');
            $table->string("registration")->default('');
            $table->string("nop")->default('');
            $table->string("payment_type")->default('');
            $table->string("bank_name")->default('');
            $table->string("branch_name")->default('');
            $table->string("account_name")->default('');
            $table->string("account_number")->default('');
            $table->string("verify")->default('');
            $table->string("password")->default('');
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
