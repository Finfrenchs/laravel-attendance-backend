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

    //checkin
    public function checkin(Request $request)
    {
        //validate lat and long
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        //save new attendance
        $attendance = new Attendance;
        $attendance->user_id = $request->user()->id;
        $attendance->date = date('Y-m-d');
        $attendance->time_in = date('H:i:s');
        $attendance->latlong_in = $request->latitude . ',' . $request->longitude;
        $attendance->save();

        return response([
            'message' => 'Checkin success',
            'attendance' => $attendance
        ], 200);
    }

    //checkout
    public function checkout(Request $request)
    {
        //validate lat and long
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        //get today attendance
        $attendance = Attendance::where('user_id', $request->user()->id)
            ->where('date', date('Y-m-d'))
            ->first();

        //check if attendance not found
        if (!$attendance) {
            return response(['message' => 'Checkin first'], 400);
        }

        //save checkout
        $attendance->time_out = date('H:i:s');
        $attendance->latlong_out = $request->latitude . ',' . $request->longitude;
        $attendance->save();

        return response([
            'message' => 'Checkout success',
            'attendance' => $attendance
        ], 200);
    }

    //check is checkedin
    public function isCheckedin(Request $request)
    {
        //get today attendance
        $attendance = Attendance::where('user_id', $request->user()->id)
            ->where('date', date('Y-m-d'))
            ->first();
        $isCheckout = $attendance ? $attendance->time_out : false;
        return response([
            'checkedin' => $attendance ? true : false,
            'checkedout' => $isCheckout ? true : false,
        ], 200);
    }
}
