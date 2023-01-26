<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObatApotek extends Model{
    protected $primaryKey = 'id';
    protected $table = 'obat_apotek';
    protected $fillable = array('nama_obat', 'stok_obat', 'harga_obat', 'deskripsi_obat', 'komposisi_obat', 'dosis_obat', 
                                'penyajian_obat', 'golongan_obat', 'efek_samping_obat','supplier_id');

    public $timestamps = true;

    public function supplier(){
        return $this->belongsTo('App\Models\Supplier');
    }

    public function transaksiapotek(){
        return $this->hasMany('App\Model\TransaksiApotek');
    }
}
?>