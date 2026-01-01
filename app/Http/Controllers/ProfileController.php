<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    //
    public function edit()
    {
        return view('profile.edit');
    }

    public function updateInfo(Request $request)
    {
        $request->validate([
            'name' => 'required|string',

        ]);

        Auth::user()->update($request->only('name', 'address', 'phone'));

        return back()->with('success', 'Data berhasil diperbarui.');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
        ]);

        Auth::user()->update(['email' => $request->email]);

        return back()->with('success', 'Email berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:6',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah']);
        }

        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::delete($user->photo);
            }
            $path = $request->file('photo')->store('photos');
            $user->update(['photo' => $path]);
        }

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }
}
