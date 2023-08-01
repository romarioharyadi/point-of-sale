<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MStok extends Model
{
    use HasFactory;

    protected $table = 'tb_stok';
    protected $primaryKey = 'idStok';
    protected $guarded = [];

    public function getProduk()
    {
        return $this->belongsTo(MProduk::class, 'idProduk', 'idProduk');
    }

    public function getSupplier()
    {
        return $this->belongsTo(MSupplier::class, 'idSupplier', 'idSupplier');
    }
}
