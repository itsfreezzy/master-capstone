<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblcustomers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 20)->unique()->nullable();
            $table->string('type', 20);
            // $table->string('company', 100)->unique()->nullable();
            $table->string('name', 100)->unique()->nullable();
            $table->string('tinnumber', 50)->unique();
            // $table->string('contactperson', 100)->unique()->nullable();
            $table->string('contactnumber', 30)->unique();
            $table->string('username', 50)->unique()->nullable();
            $table->string('email', 75)->unique()->nullable();
            $table->string('password', 100);
            $table->rememberToken();
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
        Schema::dropIfExists('tblcustomers');
    }
}
