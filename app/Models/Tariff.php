<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'tariffs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'jenis_motor',
        'durasi',
        'harga',
    ];
}
