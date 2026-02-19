<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    // Tampilkan Halaman Profil
    public function edit()
    {   
        $title = 'My Profile';
        return view('profile.app', [
            'user' => Auth::user(),
            'title' => $title
        ]);
    }
    
    // Proses Update Profil (Nama & Password)
    public function update(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed', // butuh input new_password_confirmation
        ]);

        /** @var User $user */
        $user = Auth::user();

        // 2. Update Nama
        $user->name = $request->name;

        // 3. Update Password (Jika diisi)
        if ($request->filled('new_password')) {
            // Cek apakah password lama benar
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini salah.']);
            }

            // Ganti password
            $user->password = Hash::make($request->new_password);
        }

        // 4. Simpan ke Database
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}