<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'units';
    protected $primaryKey = 'id';

    protected $fillable = [
        'jenis_motor', 
        'plat_nomor', 
        'kode_motor', 
        'status'
    ];
}
