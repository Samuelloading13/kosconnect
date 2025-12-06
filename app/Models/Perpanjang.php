<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perpanjang extends Model
{
    use HasFactory;

    protected $table = 'perpanjang';

    protected $fillable = [
        'booking_id',
        'user_id',
        'lama_perpanjang',
        'status',
    ];
}
