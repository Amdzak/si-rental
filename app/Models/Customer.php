<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'customers';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama', 
        'no_hp', 
        'alamat', 
        'foto_ktp'
    ];
}
