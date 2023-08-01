<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CafeReserv extends Model{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'name',
        'phone',
        'count',
        'description',
    ];

}
