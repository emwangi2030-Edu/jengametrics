<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UsersController;

// Registration route
Route::post('register', [UsersController::class, 'register']);

// Login route
Route::post('login', [UsersController::class, 'login']);

// Route to get the authenticated user
Route::middleware('auth:sanctum')->get('user', [UsersController::class, 'getAuthenticatedUser']);
