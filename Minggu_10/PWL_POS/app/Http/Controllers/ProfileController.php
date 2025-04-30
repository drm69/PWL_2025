<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'User Profile',
            'list' => ['Home', 'Profile']
        ];

        $page = (object) [
            'title' => 'Profile Pengguna'
        ];


        $activeMenu = 'profile';

        $user = auth()->user();

        return view('profile.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:m_user,username,' . $user->user_id . ',user_id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Update data
        $user->nama = $request->nama;
        $user->username = $request->username;

        // Update password jika diisi
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        // Update foto jika ada
        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::exists('public/profiles/' . $user->foto)) {
                Storage::delete('public/profiles/' . $user->foto);
            }

            $fotoName = time() . '.' . $request->foto->extension();
            $request->foto->storeAs('public/profiles', $fotoName);
            $user->foto = $fotoName;
        }

        /** @var \App\Models\UserModel $user */
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
