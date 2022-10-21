<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSanksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sanksi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 50);
            $table->integer('lapor')->nullable();
            $table->integer('skorsing')->nullable();
            $table->boolean('drop_out')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sanksi');
    }
}
