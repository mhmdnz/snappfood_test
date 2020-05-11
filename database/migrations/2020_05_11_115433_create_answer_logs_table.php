<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswerLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->unsigned();
            $table->integer('incoming_call_id')->unsigned()->unique();
            $table->dateTime('end_call_time')->nullable();
            $table->timestamps();
            $table->unique(array('employee_id', 'incoming_call_id'));
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('incoming_call_id')->references('id')->on('incoming_calls');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answer_logs');
    }
}
