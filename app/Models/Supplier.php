<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model{
    protected $primaryKey = 'id';
    protected $table = "supplier";
    protected $fillable = array('nama_supplier', 'alamat_supplier', 'no_telp');

    public $timestamps = true;

    public function obatapotek(){
        return $this->hasMany('App\Model\ObatApotek');
    }
}