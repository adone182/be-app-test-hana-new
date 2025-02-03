<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ThemeController extends Controller
{

    public function getBackgroundUrl()
    {
        $path = 'backgrounds/' . 'background_image.jpg';
        // Menggunakan APP_URL dan path yang benar
        $url = env('APP_URL') . '/storage/' . $path;

        return response()->json(['url' => $url]);
    }

    // Menampilkan URL logo
    public function getLogoUrl()
    {
        $path = 'logos/' . 'logo.png';
        // Menggunakan APP_URL dan path yang benar
        $url = env('APP_URL') . '/storage/' . $path;

        return response()->json(['url' => $url]);
    }

    public function setBackground(Request $request)
    {
        // Validasi input
        $request->validate([
            'background_image' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        // Simpan gambar background
        $path = $request->file('background_image')->store('public/backgrounds');

        // Kembalikan response dengan struktur yang benar
        return response()->json(['success' => true, 'message' => 'Background image updated', 'path' => $path]);
    }

    public function setLogo(Request $request)
    {
        // Validasi input
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        // Simpan logo
        $path = $request->file('logo')->store('public/logos');

        // Kembalikan response dengan struktur yang benar
        return response()->json(['success' => true, 'message' => 'Logo updated', 'path' => $path]);
    }


}
