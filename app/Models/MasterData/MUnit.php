<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MUnit extends Model
{
    use HasFactory;

    protected $table = 'tb_unit';
    protected $primaryKey = 'idUnit';
    protected $guarded = [];
}
