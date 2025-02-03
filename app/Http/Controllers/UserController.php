<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Menampilkan daftar pengguna
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Menambah pengguna baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'user_email' => 'required|email',
            'user_fullname' => 'required|string',
            'user_password' => 'required|min:6',
        ]);

        Log::info('Request data:', $request->all()); // Menampilkan data request

        // Membuat user baru
        $user = new User([
            'user_email' => $request->user_email,
            'user_fullname' => $request->user_fullname,
            'user_password' => Hash::make($request->user_password),  // Pastikan password sudah di-hash
        ]);

        // Cek apakah data berhasil disimpan
        if ($user->save()) {
            Log::info('User created:', ['user' => $user]);
            return response()->json(['message' => 'User created successfully!', 'data' => $user], 201);
        } else {
            Log::error('User creation failed');
            return response()->json(['message' => 'User creation failed'], 400);
        }
    }


    // Menampilkan detail pengguna berdasarkan ID
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // Mengupdate pengguna
    public function update(Request $request, $id)
    {
        // Mencari user berdasarkan ID
        $user = User::findOrFail($id);

        // Validasi input
        $request->validate([
            'user_email' => 'required|email|unique:users,user_email,' . $id, // Memastikan email unik kecuali untuk user yang sama
            'user_fullname' => 'required|string',
            'user_password' => 'nullable|min:6', // Password hanya di-update jika ada
        ]);

        // Update data user
        $user->user_email = $request->user_email;
        $user->user_fullname = $request->user_fullname;

        // Update password jika ada
        if ($request->has('user_password')) {
            $user->user_password = Hash::make($request->user_password); // Hash password baru
        }

        // Simpan perubahan ke database
        $user->save();

        // Response sukses
        return response()->json(['message' => 'User updated successfully!', 'data' => $user]);
    }



    // Menghapus pengguna
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully!']);
    }
}
