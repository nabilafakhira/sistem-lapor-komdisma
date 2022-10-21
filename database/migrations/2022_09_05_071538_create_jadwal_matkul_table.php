<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalMatkulTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwal_matkul', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pelanggaran_mahasiswa_id')->unsigned();
            $table->date('tanggal');
            $table->string('matkul', 100);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('dosen', 20);
            $table->string('koordinator', 20);

            $table->foreign('pelanggaran_mahasiswa_id')->references('id')->on('pelanggaran_mahasiswa')->onDelete('cascade');
            $table->foreign('dosen')->references('id')->on('pengurus')->onDelete('cascade');
            $table->foreign('koordinator')->references('id')->on('pengurus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jadwal_matkul');
    }
}
