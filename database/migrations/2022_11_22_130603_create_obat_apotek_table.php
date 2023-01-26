<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObatApotekTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obat_apotek', function (Blueprint $table) {
            $table->bigIncrements('id_obat');

            $table->string('nama_obat');
            $table->integer('stok_obat');
            $table->integer('harga_obat');
            $table->string('deskripsi_obat');
            $table->string('komposisi_obat');
            $table->string('dosis_obat');
            $table->string('penyajian_obat');
            $table->string('golongan_obat');
            $table->string('efek_samping_obat');
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
        Schema::dropIfExists('obat_apotek');
    }
}
