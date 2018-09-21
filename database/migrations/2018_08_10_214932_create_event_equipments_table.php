<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbleventequipments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reservationcode', 20);
            $table->string('equipmentcode', 20);
            $table->integer('qty');
            $table->decimal('totalprice', 8, 2)->nullable();
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
        Schema::dropIfExists('tbleventequipments');
    }
}
