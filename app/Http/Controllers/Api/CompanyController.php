<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Temukan perusahaan berdasarkan ID
        $company = Company::find($id);

        // Periksa apakah perusahaan ditemukan
        if (!$company) {
            return response()->json([
                'message' => 'Company not found',
            ], 404);
        }

        // Kembalikan detail perusahaan sebagai JSON
        return response()->json([
            'data' => $company,
            'message' => 'Company details retrieved successfully',
            'status' => 'Ok',
        ], 200);
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
