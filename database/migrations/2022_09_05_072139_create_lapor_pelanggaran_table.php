<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporPelanggaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lapor_pelanggaran', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pelanggaran_mahasiswa_id')->unsigned();
            $table->date('tanggal');
            $table->string('penerima_lapor', 20);
            $table->string('keterangan',100)->nullable();
            $table->boolean('status')->default(0);

            $table->foreign('pelanggaran_mahasiswa_id')->references('id')->on('pelanggaran_mahasiswa')->onDelete('cascade');
            $table->foreign('penerima_lapor')->references('id')->on('pengurus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lapor_pelanggaran');
    }
}
