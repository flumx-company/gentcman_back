<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdeaImprovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('idea_improvements', function (Blueprint $table) {
            $table->id();
            $table->string('email');
	    $table->string('theme');
            $table->text('improvement');
            $table->enum('status', [
                'received',
                'read',
                'for consideration',
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
        Schema::dropIfExists('idea_improvements');
    }
}
