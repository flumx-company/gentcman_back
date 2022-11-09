<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralProgramStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_program_steps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('goal')->default(10)->comment('The goal to be achieved');
            $table->integer('reward')->default(100)->comment('Bonus reward');
            $table->foreignId('referral_program_id')
                ->constrained('referral_programs')
                ->onDelete('cascade');
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
        Schema::dropIfExists('referral_program_steps');
    }
}
