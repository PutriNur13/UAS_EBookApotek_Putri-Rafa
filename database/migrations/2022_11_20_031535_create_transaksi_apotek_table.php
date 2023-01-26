<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiApotekTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_apotek', function (Blueprint $table) {
            $table->id();

            $table->integer('id_user')->index('id_user_foreign');
            $table->integer('id_obat')->index('id_obat_foreign');
            $table->integer('jumlah_obat');
            $table->integer('total_harga');
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
        Schema::dropIfExists('transaksi_apotek');
    }
}
