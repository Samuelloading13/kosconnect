<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // IZINKAN KOLOM INI DIISI DARI FORM
    protected $fillable = [
        'user_id',
        'jumlah_bayar',
        'tanggal_bayar',
        'status',
        'bukti_pembayaran',
    ];

    // Relasi ke User (Penyewa)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
