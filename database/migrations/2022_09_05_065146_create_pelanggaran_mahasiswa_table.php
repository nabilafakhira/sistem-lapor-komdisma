<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelanggaranMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelanggaran_mahasiswa', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nim',30);
            $table->enum('tingkat', ['1', '2', '3', '4']);
            $table->date('tanggal');
            $table->time('jam');
            $table->integer('lokasi_id')->unsigned();
            $table->integer('jenis_pelanggaran_id')->unsigned();
            $table->string('keterangan', 100);
            $table->string('pelapor', 20);
            $table->string('bukti_foto');
            $table->string('inspektur', 20)->nullable();
            $table->date('tgl_verifikasi')->nullable();
            $table->integer('sanksi_id')->unsigned()->nullable();
            $table->date('tgl_surat_bebas')->nullable();

            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('lokasi_id')->references('id')->on('lokasi_pelanggaran')->onDelete('cascade');
            $table->foreign('jenis_pelanggaran_id')->references('id')->on('jenis_pelanggaran')->onDelete('cascade');
            $table->foreign('pelapor')->references('id')->on('pengurus')->onDelete('cascade');
            $table->foreign('inspektur')->references('id')->on('pengurus')->onDelete('cascade');
            $table->foreign('sanksi_id')->references('id')->on('sanksi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pelanggaran_mahasiswa');
    }
}
