<?php

namespace App\Http\Controllers;

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


        $permission->is_approval = $request->input('is_approval');


        $permission->save();


        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
    }
}
