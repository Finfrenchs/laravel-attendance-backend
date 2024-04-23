<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //LOGIN API
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|max:255',
        ]);

        $user = User::where('email', $loginData['email'])->first();

        // Cek apakah pengguna ditemukan
        if (!$user) {
            return response([
                'message' => ['Invalid credentials'],
            ], 401);
        }

        // Cek apakah password benar
        if (!Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['Invalid credentials'],
            ], 401);
        }

        //Cek user
        if (!$user) {
            return response([
                'message' => ['Email not found'],
            ], 404);
        }

        //Cek password
        if (!Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['Password is wrong'],
            ], 404);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    //Logout
    public function logout(Request $request)
    {
        if (!$request->user()) {
            return response([
                'message' => ['Unauthorized'],
            ], 401);
        }

        $request->user()->tokens()->delete();

        return response([
            'message' => 'Logout successful',
        ], 200);
    }
}
