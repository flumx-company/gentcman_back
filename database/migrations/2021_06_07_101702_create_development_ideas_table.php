<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevelopmentIdeasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('development_ideas', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('email');
            $table->text('idea');
	    $table->string('theme');
            $table->enum('status', [
                'received',
                'read',
                'communication with the customer',
                'consideration',
                'taken for implementation',
                'rejected',
		'implemented'
            ])
                ->default('received');
	    
            $table->string('message')->nullable();
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
        Schema::dropIfExists('development_ideas');
    }
}
