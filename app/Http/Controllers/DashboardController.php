<?php

// namespace App\Http\Controllers;

// use App\Models\User;
// use Illuminate\Http\Request;

// class DashboardController extends Controller
// {
//     public function getStats()
//     {
//         $totalUsers = User::count();
//         $loggedInUsers = User::where('user_status', 1)->count();

//         return response()->json([
//             'total_users' => $totalUsers,
//             'logged_in_users' => $loggedInUsers
//         ]);
//     }
// }

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class DashboardController extends Controller
{
    public function getStats()
    {
        try {
            // Menghitung jumlah total pengguna
            $totalUsers = User::count();

            // Menghitung jumlah pengguna yang sedang login (status = 1)
            $loggedInUsers = User::where('user_status', 1)->count();

            // Mengembalikan response sukses dengan data
            return response()->json([
                'data' => [
                    'total_users' => $totalUsers,
                    'active_users' => $loggedInUsers
                ],
                'message' => 'Statistics retrieved successfully.'
            ], 200);

        } catch (QueryException $e) {
            // Menangani error query database
            Log::error('Database Query Error: ', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'code' => 500,
                'message' => 'Failed to retrieve statistics due to a database error.',
                'errors' => ['message' => $e->getMessage()],
            ], 500);

        } catch (\Exception $e) {
            // Menangani error umum lainnya
            Log::error('Error retrieving statistics: ', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'code' => 500,
                'message' => 'An error occurred while retrieving statistics.',
                'errors' => ['message' => $e->getMessage()],
            ], 500);
        }
    }
}
