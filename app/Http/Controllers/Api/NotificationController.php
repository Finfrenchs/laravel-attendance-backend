<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {

        $user = Auth::user();


        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Kembalikan notifikasi sebagai respons JSON
        return response()->json([
            'success' => true,
            'message' => "Notification berhasil di dapatkan",
            'data' => $notifications,
        ], 200);
    }
}
