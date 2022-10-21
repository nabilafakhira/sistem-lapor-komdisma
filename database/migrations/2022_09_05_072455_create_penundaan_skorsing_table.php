<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenundaanSkorsingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penundaan_skorsing', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pelanggaran_mahasiswa_id')->unsigned();
            $table->date('tgl_pengajuan');
            $table->string('keterangan',100);
            $table->boolean('status')->default(0);
            $table->string('jadwal_lama_id')->nullable();
            $table->string('inspektur', 20)->nullable();
            $table->string('komentar',100)->nullable();

            $table->foreign('pelanggaran_mahasiswa_id')->references('id')->on('pelanggaran_mahasiswa')->onDelete('cascade');
            $table->foreign('inspektur')->references('id')->on('pengurus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penundaan_skorsing');
    }
}
