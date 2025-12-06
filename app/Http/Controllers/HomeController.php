<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $kamar = Room::where('status', 'tersedia')->latest()->get();
        return view('welcome', compact('kamar'));
    }

    public function show($id)
    {
        $kamar = Room::findOrFail($id);
        return view('detail_kamar', compact('kamar'));
    }
}
