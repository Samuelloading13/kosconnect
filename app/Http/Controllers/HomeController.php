<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Menampilkan halaman depan dengan daftar kamar tersedia
    public function index()
    {
        $kamar = Room::where('status', 'tersedia')->latest()->get();
        return view('welcome', compact('kamar'));
    }

    // Menampilkan detail satu kamar
    public function show($id)
    {
        $kamar = Room::findOrFail($id);
        return view('detail_kamar', compact('kamar'));
    }
}
