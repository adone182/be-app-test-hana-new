<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    // Mengatur gambar background
    public function setBackground(Request $request)
    {
        // Lakukan penyimpanan gambar atau pengaturan lainnya sesuai permintaan
        $request->validate([
            'background_image' => 'required|image',
        ]);

        // Simpan gambar background sesuai permintaan
        $path = $request->file('background_image')->store('public/backgrounds');

        return response()->json(['message' => 'Background image updated', 'path' => $path]);
    }

    // Mengatur logo
    public function setLogo(Request $request)
    {
        // Lakukan penyimpanan logo sesuai permintaan
        $request->validate([
            'logo' => 'required|image',
        ]);

        // Simpan logo sesuai permintaan
        $path = $request->file('logo')->store('public/logos');

        return response()->json(['message' => 'Logo updated', 'path' => $path]);
    }

    // Mengatur menu
    public function setMenu(Request $request)
    {
        // Lakukan penyimpanan pengaturan menu sesuai permintaan
        $request->validate([
            'menu_items' => 'required|array',
        ]);

        // Proses pengaturan menu (misalnya, simpan ke database atau file konfigurasi)
        // Berikut hanya contoh respons
        return response()->json(['message' => 'Menu updated successfully']);
    }
}
