<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input data
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'time_in' => 'required|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i|after_or_equal:time_in',
            'latlong_in' => 'required|string',
            'latlong_out' => 'nullable|string',
        ]);

        // Ambil data user yang sesuai dengan ID yang diberikan
        $user = User::findOrFail($request->user_id);

        // Cek apakah data tanggal dan waktu kehadiran sudah ada sebelumnya
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $request->date)
            ->where('time_in', $request->time_in)
            ->first();

        if ($existingAttendance) {
            return Response::json([
                'error' => 'Attendance already exists for the provided user and time in on the given date.'
            ], 400);
        }

        // Buat data kehadiran baru
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => $request->date,
            'time_in' => $request->time_in,
            'time_out' => $request->time_out,
            'latlong_in' => $request->latlong_in,
            'latlong_out' => $request->latlong_out,
        ]);

        // Mengembalikan respons JSON
        return response()->json([
            'data' => $attendance,
            'message' => 'Attendance created successfully',
            'status' => 'Created'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
