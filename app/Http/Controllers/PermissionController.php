<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions = Permission::with('user')
            ->when($request->input('name'), function ($query, $name) {
                return $query->whereHas('user', function ($query) use ($name) {
                    $query->where('name', 'like', '%' . $name . '%');
                });
            })
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('pages.permissions.index', compact('permissions'));
    }

    public function show($id)
    {

        $permission = Permission::with('user')->findOrFail($id);


        return view('pages.permissions.show', compact('permission'));
    }

    public function edit($id)
    {

        $permission = Permission::findOrFail($id);


        return view('pages.permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'is_approval' => 'required|boolean',
        ]);


        $permission = Permission::findOrFail($id);
        $oldIsApproval = $permission->is_approval;

        $permission->is_approval = $request->input('is_approval');
        $permission->save();


        if ($permission->is_approval !== $oldIsApproval) {
            $message = $permission->is_approval ? 'Permission has been approved.' : 'Permission has been denied.';
            Notification::create([
                'user_id' => $permission->user_id,
                'permission_id' => $permission->id,
                'message' => $message,
            ]);
        }


        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
    }
}
