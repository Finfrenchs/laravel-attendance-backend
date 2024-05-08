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

    //update image profile & face_embedding
    public function updateProfile(Request $request)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'face_embedding' => 'required',
        ]);

        $user = $request->user();
        $image = $request->file('image');
        $face_embedding = $request->face_embedding;

        //save image
        $image->storeAs('public/images', $image->hashName());
        $user->image = $image->hashName();
        $user->face_embedding = $face_embedding;
        $user->save();

        return response([
            'message' => 'Profile updated',
            'user' => $user,
        ], 200);
    }

    public function updateFcmId(Request $request)
    {
        // Validate the request...
        $validated = $request->validate([
            'fcm_id' => 'required',
        ]);

        $user = $request->user();
        $user->fcm_id = $validated['fcm_id'];
        $user->save();

        return response()->json([
            'message' => 'FCM ID updated',
        ], 200);
    }
}
