<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $users = User::paginate($perPage);

        return response()->json([
            'data' => UserResource::collection($users),
            'message' => 'Users retrieved successfully.',
            'pagination' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'last_page' => $users->lastPage(),
            ],
        ]);
    }

    public function show($id): JsonResponse
    {
        $user = User::findOrFail($id);

        return response()->json([
            'data' => new UserResource($user),
            'message' => 'User retrieved successfully.',
        ]);
    }
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            // Melakukan validasi data menggunakan UserRequest
            $validatedData = $request->validated();
            Log::info('Data setelah validasi:', $validatedData);

            // Membuat user baru
            $user = User::create($validatedData);
            Log::info('User berhasil disimpan:', ['data' => $user]);

            // Mengembalikan response sukses
            return response()->json([
                'data' => new UserResource($user),
                'message' => 'User created successfully.',
            ], 201);

        } catch (ValidationException $e) {
            // Menangani error validasi
            Log::error('Validation Error:', $e->errors());

            return response()->json([
                'code' => 422,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Menangani error umum
            Log::error('Error storing user data: ', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'code' => 500,
                'errors' => ['message' => 'Failed to create user.']
            ], 500);
        }
    }


    // Mengupdate pengguna
    public function update(UpdateUserRequest $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            Log::info('User ditemukan:', ['data' => $user]);

            Log::info('Data sebelum validasi:', $request->all());

            $validatedData = $request->validated();
            Log::info('Data setelah validasi:', $validatedData);

            $user->update($validatedData);
            Log::info('User berhasil diupdate:', ['data' => $user]);

            return response()->json([
                'data' => new UserResource($user),
                'message' => 'User updated successfully.',
            ], 200);

        } catch (ValidationException $e) {
            // Menangani error validasi
            Log::error('Validation Error:', $e->errors());

            return response()->json([
                'code' => 422,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating user data: ', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'code' => 500,
                'errors' => ['message' => 'Failed to update user.']
            ], 500);
        }
    }


    public function updateStatus($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $newStatus = $user->user_status == 1 ? 0 : 1;
            $user->update(['user_status' => $newStatus]);

            // Menyimpan hasil perubahan status
            return response()->json([
                'user_id' => $user->user_id,
                'user_status' => $newStatus,
                'message' => 'User status updated successfully.',
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'code' => 404,
                'message' => 'User not found.',
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Failed to update user status.',
                'errors' => ['message' => $e->getMessage()],
            ], 500);
        }
    }



    // Menampilkan daftar pengguna
    // public function index()
    // {
    //     $users = User::all();
    //     return response()->json($users);
    // }

    // Menampilkan detail pengguna berdasarkan ID
    // public function show($id): JsonResponse 
    // {
    //     // $user = User::findOrFail($id);
    //     // return response()->json($user);
    // }

    // Menambah pengguna baru
    // public function store(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'user_email' => 'required|email',
    //         'user_fullname' => 'required|string',
    //         'user_password' => 'required|min:6',
    //         'user_nohp' => 'nullable|string|unique:users,user_nohp',
    //     ]);

    //     Log::info('Request data:', $request->all()); // Menampilkan data request

    //     // Membuat user baru
    //     $user = new User([
    //         'user_email' => $request->user_email,
    //         'user_fullname' => $request->user_fullname,
    //         'user_password' => Hash::make($request->user_password), 
    //         'user_nohp' => $request->user_nohp ?? null,
    //     ]);

    //     // Cek apakah data berhasil disimpan
    //     if ($user->save()) {
    //         Log::info('User created:', ['user' => $user]);
    //         return response()->json(['message' => 'User created successfully!', 'data' => $user], 201);
    //     } else {
    //         Log::error('User creation failed');
    //         return response()->json(['message' => 'User creation failed'], 400);
    //     }
    // }

    // Mengupdate pengguna
    // public function update(Request $request, $id)
    // {
    //     // Mencari user berdasarkan ID
    //     $user = User::findOrFail($id);

    //     // Validasi input
    //     $request->validate([
    //         'user_email' => 'sometimes|email|unique:users,user_email,' . $id, // Memastikan email unik kecuali untuk user yang sama
    //         'user_fullname' => 'required|string',
    //         'user_password' => 'nullable|min:6', 
    //         'user_nohp' => 'nullable|string|unique:users,user_nohp,' . $id,
    //     ]);

    //     // Update data user
    //     $user->user_email = $request->user_email;
    //     $user->user_fullname = $request->user_fullname;

    //     // Update password jika ada
    //     if ($request->has('user_password')) {
    //         $user->user_password = Hash::make($request->user_password); // Hash password baru
    //     }

    //     if ($request->has('user_nohp')){
    //         $user->user_nohp = $request->user_nohp;
    //     }

    //     if ($user->save()) {
    //         Log::info('User created:', ['user' => $user]);
    //         return response()->json(['message' => 'User created successfully!', 'data' => $user], 201);
    //     } else {
    //         Log::error('User creation failed');
    //         return response()->json(['message' => 'User creation failed'], 400);
    //     }

    //     // Response sukses
    //     return response()->json(['message' => 'User updated successfully!', 'data' => $user]);
    // }

    // Menghapus pengguna
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully!']);
    }
}
