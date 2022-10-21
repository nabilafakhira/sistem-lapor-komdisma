<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratKelakuanBaikTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_kelakuan_baik', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nim',30);
            $table->date('tgl_pengajuan');
            $table->string('keperluan', 100);
            $table->string('inspektur', 20)->nullable();
            $table->date('tgl_berakhir')->nullable();
            $table->boolean('status')->default(0);
            $table->string('komentar',100)->nullable();

            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
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
        Schema::dropIfExists('surat_kelakuan_baik');
    }
}
