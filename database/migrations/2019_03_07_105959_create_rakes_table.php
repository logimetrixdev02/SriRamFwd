<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRakesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rakes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('master_rake_id');
            $table->string('rake_no');
            $table->string('quantity');
            $table->integer('product_id');
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
        Schema::dropIfExists('rakes');
    }
}
