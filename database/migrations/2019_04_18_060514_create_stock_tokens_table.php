<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id');
            $table->integer('dealer_id')->nullable();
            $table->date('date_of_generation');
            $table->integer('warehouse_id')->nullable();
            $table->integer('rate')->nullable();
            $table->integer('product_id');
            $table->integer('quantity');
            $table->integer('unit_id');
            $table->integer('transporter_id')->nullable();
            $table->integer('warehouse_keeper_id')->nullable();
            $table->string('truck_number')->nullable();
            $table->mediumText('description')->nullable();
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
        Schema::dropIfExists('stock_tokens');
    }
}
