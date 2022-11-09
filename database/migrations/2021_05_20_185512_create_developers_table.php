<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevelopersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('developers', function (Blueprint $table) {
            $table->id();
            $table->enum('position', [
                'developer',
                'designer',
                'owner',
                'founder',
                'analyst',
                'pm',
                'qa'
            ]);
            $table->string('resource_link')->nullable();
	    $table->string('email')->nullable();
	    $table->string('first_name');
	        $table->string('image_link');
            $table->string('last_name');
            $table->json('information')
                    ->nullable();
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
        Schema::dropIfExists('developers');
    }
}
