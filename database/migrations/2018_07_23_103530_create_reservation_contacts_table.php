<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblreservationcontacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reservationcode', 20)->nullable();
            $table->string('contactname', 100)->nullable();
            $table->string('telno', 10)->nullable();
            $table->string('mobno', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('address', 250)->nullable();
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
        Schema::dropIfExists('tblreservationcontacts');
    }
}
