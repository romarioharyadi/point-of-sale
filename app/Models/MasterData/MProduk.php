<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MProduk extends Model
{
    use HasFactory;

    protected $table = 'tb_produk';
    protected $primaryKey = 'idProduk';
    protected $guarded = [];

    public function getKategori()
    {
        return $this->belongsTo(MKategori::class, 'idKategori', 'idKategori');
    }

    public function getUnit()
    {
        return $this->belongsTo(MUnit::class, 'idUnit', 'idUnit');
    }
}
