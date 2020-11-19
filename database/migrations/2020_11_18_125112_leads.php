<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Leads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lead_id');
            $table->integer('contact_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->integer('pipeline_id')->nullable();
            $table->integer('customer_id')->nullable();
            //$table->integer('sale')->nullable();
            $table->string('name')->nullable();
            $table->string('teacher')->nullable();
            $table->string('method')->nullable();
            $table->string('languange')->nullable();
            $table->dateTime('datetime_trial')->nullable();
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
        Schema::dropIfExists('leads');
    }
}
