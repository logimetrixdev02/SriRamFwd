<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRakeProductAllotmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rake_product_allotment_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('rake_product_allotment_id');
            $table->integer('unit_id');
            $table->integer('product_id');
            $table->integer('alloted_quantity');
            $table->integer('remaining_quantity');
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
        Schema::dropIfExists('rake_product_allotment_details');
    }
}
