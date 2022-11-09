<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                    ->nullable()
                    ->constrained('orders')
                    ->onDelete('cascade');

            $table->foreignId('product_id')
                    ->nullable()
                    ->constrained('products')
                    ->onDelete('set null');

            $table->foreignId('coupon_id')
                    ->nullable()
                    ->constrained('coupons')
                    ->onDelete('set null');

            $table->integer('quantity');

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
        Schema::dropIfExists('order_products');
    }
}
