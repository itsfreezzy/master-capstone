<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblequipments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50)->unique()->nullable();
            $table->string('name', 100)->unique();
            $table->string('description', 150)->nullable();
            $table->decimal('wholedayrate', 6, 2);
            $table->decimal('halfdayrate', 6, 2);
            $table->decimal('hourlyexcessrate', 6, 2);
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
        Schema::dropIfExists('tblequipments');
    }
}
