<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WarehouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_warehouse', function (Blueprint $table) {
            $table->id();
            $table->string('name',255)->nullable();
            $table->integer('customer_id');
            $table->mediumText('description')->nullable();
            $table->string('address', 255)->nullable();
            $table->bigInteger('total_product')->default(0)->comment('Tồn kho');
            $table->bigInteger('total_import')->default(0)->comment('Tổng số lượng nhập');
            $table->string('status', 60)->default('active');
            $table->timestamps();
        });
        Schema::create('ec_product_warehouse', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('warehouse_id');
            $table->integer('qty')->default(0);
            $table->string('status', 60)->default('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ec_warehouse');
        Schema::dropIfExists('ec_product_warehouse');
    }
}
