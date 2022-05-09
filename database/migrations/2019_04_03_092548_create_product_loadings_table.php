<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductLoadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_loadings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('token_id');
            $table->integer('product_company_id');
            $table->string('product_company_name')->nullable();
            $table->integer('wagon_number');
            $table->integer('product_id');
            $table->string('product_name')->nullable();
            $table->integer('quantity');
            $table->integer('transporter_id');
            $table->string('transporter_name')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->integer('dealer_id');
            $table->string('dealer_name')->nullable();
            $table->integer('truck_number');
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
        Schema::dropIfExists('product_loadings');
    }
}
