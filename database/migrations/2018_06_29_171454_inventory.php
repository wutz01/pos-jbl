<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Inventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('inventory', function (Blueprint $table) {
        $table->increments('id');
        $table->string('medicineName');
        $table->string('medicineType')->nullable();
        $table->text('description')->nullable();
        $table->decimal('pricePerPiece', 19,2);
        $table->decimal('bulkPrice', 19,2);
        $table->decimal('supplierPrice', 19,2);
        $table->string('supplierName')->nullable();
        $table->integer('stockQty')->unsigned()->default(0);
        $table->string('status')->comment('ACTIVE/INACTIVE')->default('ACTIVE');
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
      Schema::dropIfExists('inventory');
    }
}
