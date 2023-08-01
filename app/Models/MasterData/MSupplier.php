<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MSupplier extends Model
{
    use HasFactory;

    protected $table = 'tb_supplier';
    protected $primaryKey = 'idSupplier';
    protected $guarded = [];
}
