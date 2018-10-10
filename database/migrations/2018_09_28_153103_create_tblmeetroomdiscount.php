<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblmeetroomdiscount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblmeetroomdiscount', function (Blueprint $table) {
            $table->increments('id');
            $table->string('timeblockcode', 30);
            $table->string('code', 50)->unique()->nullable();
            $table->string('name', 75)->unique();
            $table->decimal('floorarea', 6, 2);
            $table->integer('mincapacity');
            $table->integer('maxcapacity');
            $table->decimal('rateperblock', 8, 2);
            $table->decimal('ineghourlyrate', 7, 2);
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
        Schema::dropIfExists('tblmeetroomdiscount');
    }
}
