<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                    ->nullable()
                    ->constrained('users')
                    ->onDelete('cascade');

            $table->foreignId('order_status_id')
                    ->nullable()
                    ->constrained('order_statuses')
                    ->onDelete('set null');

            $table->foreignId('coupon_id')
                    ->nullable()
                    ->constrained('coupons')
                    ->onDelete('set null');

            $table->float('grand_total', 10, 2);
            $table->float('shipping_cost', 10, 2)->nullable();
            $table->string('billing_email');
            $table->string('billing_phone');
	    $table->string('billing_city');
            $table->string('billing_user_name');
            $table->string('billing_street')->nullable();
	    $table->string('billing_house')->nullable();
            $table->string('billing_apartment')->nullable();
	    $table->string('message_from_user')->nullable();
	    $table->string('message')->nullable();
	    $table->string('department')->nullable();
            $table->integer('billing_delivery_type');
            $table->integer('billing_payment_type');
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
        Schema::dropIfExists('orders');
    }
}
