<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getStats()
    {
        $totalUsers = User::count();
        $loggedInUsers = User::where('user_status', 1)->count();

        return response()->json([
            'total_users' => $totalUsers,
            'logged_in_users' => $loggedInUsers
        ]);
    }
}
