<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblpayments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('paymentcode', 20)->nullable()->unique();
            $table->string('reservationcode', 20);
            $table->string('paymenttype', 75);
            $table->datetime('paymentdate');
            $table->decimal('amount', 7, 2);
            $table->string('status', 30);
            $table->string('proof', 100);
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
        Schema::dropIfExists('tblpayments');
    }
}
