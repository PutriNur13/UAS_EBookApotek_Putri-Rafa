<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiApotek extends Model{
    protected $table = 'transaksi_apotek';
    protected $fillable = array('user_id', 'obat_id', 'jumlah_obat', 'total_harga');

    public $timestamps = true;

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function obat(){
        return $this->belongsTo('App\Models\ObatApotek');
    }
}
?>