<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\PersonalAccessTokenResult;

class UsersController extends Controller
{
    // User Registration
    public function register(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6', // added password confirmation
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate a token after registration using Passport
        $token = $user->createToken('appToken')->accessToken; // For Passport
        // If using Sanctum, use: $token = $user->createToken('appToken')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'token' => $token
        ], 201);
    }

    // User Login
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        // Find user by email
        $user = User::where('email', $request->email)->first();
    
        // Verify user and password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }
    
        // Generate API token
        $token = $user->createToken('auth_token')->plainTextToken;
    
        // Return response with only necessary user details
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'token' => $token
        ]);
    }
    

    // Get Authenticated User
    public function getAuthenticatedUser(Request $request)
    {
        $user = $request->user();
    
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }
    
}
