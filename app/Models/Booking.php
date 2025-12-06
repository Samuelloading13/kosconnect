<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'tanggal_mulai_kos',
        'durasi_sewa',
        'status',
        'catatan',
        'ktp_foto',
        'tanggal_berakhir_kos',
    ];

    protected $casts = [
        'tanggal_mulai_kos' => 'date',
        'tanggal_berakhir_kos' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
