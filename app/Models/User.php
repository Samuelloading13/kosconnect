<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Pastikan 'role' ditambahkan agar tidak error saat mass assignment
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==========================================
    // TAMBAHKAN RELASI DI BAWAH INI
    // ==========================================

    /**
     * Relasi: Satu User bisa memiliki banyak Booking
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Relasi: Satu User bisa memiliki banyak Payment
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relasi: Satu User bisa memiliki banyak Laporan (Report)
     * (Opsional: Tambahkan sekalian untuk fitur Laporan nanti)
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
