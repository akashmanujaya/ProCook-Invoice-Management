<?php

namespace App\Http\Controllers\Auth\API;

use App\Helpers\ApiResponse;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

/**
 * Class UserController
 *
 * This controller handles user registration, login, profile retrieval, and logout functionalities.
 *
 * @package App\Http\Controllers\Auth\API
 */
class UserController
{
    /**
     * Register a new user.
     *
     * @param Request $request The request object containing user details.
     * @return \Illuminate\Http\JsonResponse The API response with a success message and token.
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            $token = $user->createToken('API Token')->accessToken;
    
            $user = Auth::user();
    
            return ApiResponse::send(['token' => $token], 'Registration successful.');
        } catch (\Throwable $th) {
            return ApiResponse::error('Registration failed: ' . $th->getMessage(), 500);
        }
    }

    /**
     * Login an existing user.
     *
     * @param Request $request The request object containing login credentials.
     * @return \Illuminate\Http\JsonResponse The API response with a success message and token.
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                $token = $user->createToken('API Token')->accessToken;
                return ApiResponse::send(['token' => $token], 'Login successful.');
            } else {
                return ApiResponse::error('Invalid Credentials', 401);
            }
        } catch (\Throwable $th) {
            return ApiResponse::error('Login failed: ' . $th->getMessage(), 500);
        }
    }

    /**
     * Get the authenticated user's profile.
     *
     * @param Request $request The request object.
     * @return \Illuminate\Http\JsonResponse The API response with the user's profile data.
     */
    public function profile(Request $request)
    {
        try {
            $currentUser = new UserResource($request->user());
            return ApiResponse::send($currentUser, 'Data retrieved successfully.');
        } catch (\Throwable $th) {
            return ApiResponse::error('Unexpected Error: ' . $th->getMessage(), 500);
        }
    }

    /**
     * Logout the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse The API response with a success message.
     */
    public function logout()
    {
        try {
            Auth::user()->token()->revoke();
            return ApiResponse::send([], 'Logout successful.');
        } catch (\Throwable $th) {
            return ApiResponse::error('Logout failed: ' . $th->getMessage(), 500);
        }
    }
}
