<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblreservations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 20)->unique()->nullable();
            $table->unsignedInteger('reservationinfoid');
            $table->string('customercode', 20);
            $table->timestamp('datefiled');
            $table->string('status', 30); 
            $table->string('eventtitle', 100);
            $table->date('eventdate');
            $table->string('eventorganizer', 100);
            $table->string('eocontactno', 100);
            $table->string('eoemail', 100);
            $table->unsignedInteger('approvedby')->nullable();
            $table->boolean('isDone')->nullable();
            $table->dateTime('dateMarkedAsDone')->nullable();
            $table->string('cancelGrounds', 191)->nullable();
            $table->decimal('total', 9, 2)->nullable();
            $table->decimal('balance', 9, 2)->nullable();
            $table->decimal('paid', 9, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblreservations');
    }
}
