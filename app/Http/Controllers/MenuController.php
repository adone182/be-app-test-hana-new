<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    // Menu statis di controller
    protected $menuItems = [
        ['name' => 'Dashboard', 'route' => 'dashboard', 'order' => 1],
        ['name' => 'Master Pengguna', 'route' => 'users', 'order' => 2],
        ['name' => 'Settings', 'route' => 'settings', 'order' => 3],
    ];

    // Mengambil menu yang sudah diurutkan
    public function getMenu()
    {
        $menuItems = collect($this->menuItems)->sortBy('order');
        return response()->json($menuItems);
    }

    // Mengubah urutan menu
    public function updateMenuOrder(Request $request)
    {
        // Validasi input yang diterima
        $request->validate([
            'menu_items' => 'required|array', 
            'menu_items.*.name' => 'required|string', 
            'menu_items.*.route' => 'required|string', 
            'menu_items.*.order' => 'required|integer',
        ]);

        // Menyusun ulang urutan menu berdasarkan input
        foreach ($request->menu_items as $menuItem) {
            foreach ($this->menuItems as &$item) {
                if ($item['name'] == $menuItem['name']) {
                    $item['order'] = $menuItem['order'];
                }
            }
        }

        // Mengurutkan kembali menu berdasarkan urutan terbaru
        $menuItems = collect($this->menuItems)->sortBy('order')->values()->toArray();

        return response()->json([
            'message' => 'Menu order updated successfully',
            'menu' => $menuItems,
        ]);
    }


}
