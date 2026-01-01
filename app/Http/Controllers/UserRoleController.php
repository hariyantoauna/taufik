<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class UserRoleController extends Controller
{
    //
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();

        return view('users.roles.index', compact('users', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $user->syncRoles($request->roles); // sinkronisasi role
        return back()->with('success', 'Role berhasil diperbarui');
    }
}
