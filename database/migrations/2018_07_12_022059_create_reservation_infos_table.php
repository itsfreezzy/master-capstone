<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblreservationinfo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('numofattendees');
            $table->time('timestart');
            $table->time('timeend');
            $table->time('timeingress');
            $table->time('timeeggress');
            $table->date('dateingress')->nullable();
            $table->date('dateeggress')->nullable();
            $table->string('eventsetup', 75);
            $table->string('eventnature', 75);
            $table->string('caterer', 75);
            $table->boolean('isaccredited');
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
        Schema::dropIfExists('tblreservationinfo');
    }
}
