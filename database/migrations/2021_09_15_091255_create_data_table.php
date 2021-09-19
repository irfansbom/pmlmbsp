<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data', function (Blueprint $table) {
            $table->id();
            $table->string('nks');
            $table->double('dokumen_diterima');
            $table->double('dokumen_diserahkan');
            $table->text('deskripsi');
            $table->string('kode_prov');
            $table->string('kode_kabupaten');
            $table->string('kode_kecamatan');
            $table->string('kode_desa');
            $table->string('kp');
            $table->string('nbs');
            $table->string('jml_rt');
            $table->string('sumber');
            $table->string('pcl');
            $table->string('pml');
            $table->text('user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data');
    }
}
