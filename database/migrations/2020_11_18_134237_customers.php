<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Customers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lead_id')->nullable();
            $table->integer('contact_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->integer('customer_id');
            $table->integer('is_study');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('languange')->nullable();
            $table->string('loyalty')->nullable();
            $table->string('method')->nullable();
            $table->string('teacher')->nullable();
            $table->string('sex')->nullable();
            $table->string('age')->nullable();
            $table->string('datetime_trial')->nullable();

            $table->string('os_learner')->nullable();//ОС от ученика
            $table->string('os_teacher')->nullable();//ОС от преподавателя
            $table->string('count_lessons')->nullable();//Кол-во занятий
            $table->string('rate')->nullable();//Пакет
            $table->string('count_mouth')->nullable();//Кол-во месяцев
            $table->string('date_start')->nullable();//Дата начала обучения
            $table->string('date_finish')->nullable();//Дата окончания обучения
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
        Schema::dropIfExists('customers');
    }
}
