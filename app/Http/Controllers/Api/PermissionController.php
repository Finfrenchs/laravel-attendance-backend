<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PermissionController extends Controller
{
    // GET /api/permissions
    public function index(Request $request)
    {
        $permissions = Permission::with('user')
            ->when($request->input('name'), function ($query, $name) {
                return $query->whereHas('user', function ($query) use ($name) {
                    $query->where('name', 'like', '%' . $name . '%');
                });
            })
            ->orderBy('id', 'desc')
            ->get();


        return response()->json([
            'data' => $permissions,
            'message' => 'Daftar izin berhasil diambil',
            'status' => 'success'
        ], 200);
    }

    // GET /api/permissions/{id}
    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        return response()->json([
            'data' => $permission,
            'message' => 'Daftar izin karyawan berhasil diambil',
            'status' => 'success'
        ], 200);
    }

    // POST /api/permissions
    public function store(Request $request)
    {

        $request->validate([
            'date_permission' => 'required|string',
            'reason' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Buat instance Permission baru
        $permission = new Permission();
        $permission->user_id = $request->user()->id;
        $permission->date_permission = $request->input('date_permission');
        $permission->reason = $request->input('reason');
        // Set is_approval to 0 (false)
        $permission->is_approval = 0;

        // Jika ada file gambar yang diunggah
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->file('image')->storeAs('public/permissions', $imageName);
            $permission->image = $imageName;
        }


        $permission->save();


        if ($permission) {
            return response()->json([
                'success' => true,
                'message' => 'Permission Created',
                'data' => $permission
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Permission Failed to Save',
            ], 409);
        }
    }


    // PUT /api/permissions/{id}
    public function update(Request $request, $id)
    {

        $permission = Permission::find($id);


        $request->validate([
            'date_permission' => 'sometimes|required|string',
            'reason' => 'sometimes|required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Perbarui data permission
        $permission->date_permission = $request->input('date_permission', $permission->date_permission);
        $permission->reason = $request->input('reason', $permission->reason);
        $permission->is_approval = 0;


        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($permission->image) {
                Storage::disk('public')->delete($permission->image);
            }

            // Simpan gambar baru
            $imageName = time() . '.' . $request->image->extension();
            $request->file('image')->storeAs('permissions', $imageName, 'public');
            $permission->image = $imageName;
        }


        $permission->save();
        $permission->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Permission updated successfully',
            'data' => $permission
        ], 200);
    }
    // DELETE /api/permissions/{id}
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);

        // Delete image if it exists
        if ($permission->image) {
            Storage::disk('public')->delete($permission->image);
        }

        $permission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Permission deleted successfully'], 200);
    }
}
