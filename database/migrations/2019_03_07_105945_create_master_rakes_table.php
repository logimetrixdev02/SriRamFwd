<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterRakesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_rakes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('session_id');
            $table->integer('product_company_id');
            $table->string('loading_time')->nullable();
            $table->string('unloading_time')->nullable();
            $table->date('date');
            $table->integer('is_active')->default(true);
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
        Schema::dropIfExists('master_rakes');
    }
}
