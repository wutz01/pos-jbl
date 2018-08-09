<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryTrailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_trails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('productId')->unsigned();
            $table->foreign('productId')->references('id')->on('inventory');
            $table->string('type')->comment('ADD / UPDATE / ARCHIVE / ORDERED');
            $table->text('message')->nullable();
            $table->integer('updatedBy')->unsigned();
            $table->foreign('updatedBy')->references('id')->on('users');
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
        Schema::dropIfExists('inventory_trails');
    }
}
