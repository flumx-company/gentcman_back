<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('cost');
            $table->string('meta_title');
            $table->string('meta_description');
            $table->string('meta_keywords');
            $table->integer('amount')->default(0);
            $table->integer('rating')->default(0);
            $table->longText('description');
	    $table->string('product_info_image')->nullable();
            $table->json('content');
	        $table->json('images_content');
	        $table->string('banner_image')->nullable();
            $table->boolean('published')->default(false);
            $table->foreignId('product_status_id')
                    ->nullable()
                    ->constrained('product_statuses')
                    ->onDelete('set null');
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
        Schema::dropIfExists('products');
    }
}
