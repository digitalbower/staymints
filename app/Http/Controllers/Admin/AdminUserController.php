<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = Admin::with('role')
            ->whereIn('user_role_id', [2, 3, 4])
            ->latest()
            ->get();

        return view('admin.users.index')->with(['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::whereIn('id', [2, 3, 4])->get();
        $permissions = Permission::all();
        return view('admin.users.create')->with(['roles'=>$roles,'permissions'=>$permissions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6|confirmed',
            'user_role_id' => 'required|exists:roles,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password); 
        $admin = Admin::create($data); 
        if ($request->has('permissions')) {
            $admin->permissions()->sync($request->permissions);
        }
        return redirect()->route('admin.users.index')->with('success', 'New Admin User created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $user)
    {
        $roles = Role::whereIn('id', [2, 3, 4])->get();
        $permissions = Permission::all();
        return view('admin.users.edit')->with(['user'=>$user,'roles'=>$roles,'permissions'=>$permissions]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Admin $user)
    {
         $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:admins,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'user_role_id' => 'required',
            'user_role_id' => 'required|exists:roles,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $data = $request->all();
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data); 
        if ($request->has('permissions')) {
            $user->permissions()->sync($request->permissions);
        } else {
            $user->permissions()->sync([]);
        }
       
        return redirect()->route('admin.users.index')->with('success', ' Admin User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $user)
    {
        $user->delete(); 
        return redirect()->route('admin.users.index')->with('success', ' Admin User deleted successfully!');
    }
}
