<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabourPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labour_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('token_id');
            $table->string('labour_name');
            $table->integer('product_loading_id');
            $table->integer('product_id');
            $table->string('product_name');
            $table->integer('quantity');
            $table->integer('unit_id');
            $table->string('unit_name');
            $table->string('truck_number');
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
        Schema::dropIfExists('labour_payments');
    }
}
