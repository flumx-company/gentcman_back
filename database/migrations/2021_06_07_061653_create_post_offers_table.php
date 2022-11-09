<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_offers', function (Blueprint $table) {
            $table->id();
            $table->string('theme');
            $table->text('text');
            $table->string('user_name');
            $table->string('email');
            $table->string('message')->nullable();
            $table->enum('status', [
                    'received',
                    'read',
                    'for consideration',
                    'taken for implementation',
                    'rejected',
		    'implemented'
                ])
                ->default('received');
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users');
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
        Schema::dropIfExists('post_offers');
    }
}
