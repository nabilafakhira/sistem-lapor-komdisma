<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisPelanggaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_pelanggaran', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('kategori_pelanggaran_id')->unsigned();
            $table->string('nama', 30);

            $table->foreign('kategori_pelanggaran_id')->references('id')->on('kategori_pelanggaran')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jenis_pelanggaran');
    }
}
