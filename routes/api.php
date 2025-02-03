<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\MenuController;

/*
|----------------------------------------------------------------------
| API Routes
|----------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes (login & logout)
Route::post('/login', [AuthController::class, 'login']);

// Routes that require authentication (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Dashboard stats route
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);

    // User management routes
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::patch('/users/{id}/status', [UserController::class, 'updateStatus']);

    // Theme management routes
    Route::get('/theme/background', [ThemeController::class, 'getBackgroundUrl']);
    Route::get('/theme/logo', [ThemeController::class, 'getLogoUrl']);
    Route::post('/theme/background', [ThemeController::class, 'setBackground']);
    Route::post('/theme/logo', [ThemeController::class, 'setLogo']);

    // Menu management routes
    Route::get('/menu', [MenuController::class, 'getMenu']);
    Route::put('/menu/order', [MenuController::class, 'updateMenuOrder']);
});

