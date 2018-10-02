<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblfunchalldiscount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblfunchallsdiscount', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50)->unique()->nullable();
            $table->string('name', 250)->unique();
            $table->decimal('floorarea', 6, 2);
            $table->integer('mincapacity');
            $table->integer('maxcapacity');
            $table->decimal('wholedayrate', 8, 2);
            $table->decimal('halfdayrate', 7, 2);
            $table->decimal('ineghourlyrate', 7, 2);
            $table->decimal('hourlyexcessrate', 7, 2);
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
        Schema::dropIfExists('tblfunchallsdiscount');
    }
}
