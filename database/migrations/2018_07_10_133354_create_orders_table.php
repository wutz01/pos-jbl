<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('senior_citizen_list', function (Blueprint $table) {
          $table->increments('id');
          $table->string('seniorCitizen')->nullable();
          $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('serverId')->unsigned();
            $table->foreign('serverId')->references('id')->on('users');
            $table->integer('scId')->comment('Senior Citizen ID')->default(0);
            $table->integer('totalQuantity')->unsigned()->default(0);
            $table->integer('globalDiscount')->unsigned()->default(0);
            $table->decimal('grossPrice', 19, 2)->default(0);
            $table->decimal('netPrice', 19, 2)->default(0);
            $table->string('status')->comment('CURRENT / SALE')->default('CURRENT');
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('orderId')->unsigned();
          $table->foreign('orderId')->references('id')->on('orders');
          $table->integer('productId')->unsigned();
          $table->foreign('productId')->references('id')->on('inventory');
          $table->integer('quantity')->unsigned();
          $table->boolean('isBulk')->comment('if true use Bulk Price');
          $table->decimal('pricePerPiece', 19, 2);
          $table->decimal('bulkPrice', 19, 2);
          $table->integer('discount');
          $table->boolean('isMarkup')->comment('if true use Unit + mark%');
          $table->integer('markup');
          $table->decimal('totalPrice', 19, 2);
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
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('senior_citizen_list');
    }
}
