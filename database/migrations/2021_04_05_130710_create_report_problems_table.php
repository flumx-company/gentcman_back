<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_problems', function (Blueprint $table) {
            $table->id();
            $table->string('theme');
            $table->text('content');
            $table->string('message');
            $table->string('user_email');
            $table->enum('status', [
                'received',
                'read',
                'consideration',
                'in the process of fixing',
                'fixed',
                'rejected'
            ])
                ->default('received');
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
        Schema::dropIfExists('report_problems');
    }
}
