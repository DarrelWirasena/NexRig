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
        return view('profile.app', [
            'user' => Auth::user()
        ]);
    }
    
    // Tambahkan method ini di dalam class ProfileController
    public function address()
    {
        // Karena belum ada tabel address, kita langsung return view saja.
        // Nanti di sini kita akan ambil data: $addresses = auth()->user()->addresses;
        return view('profile.address');
    }

    public function createAddress()
    {
        // Kita tidak mengirim variable $address, jadi view akan mendeteksi ini sebagai "Create New"
        return view('profile.address-form');
    }

    public function editAddress($id)
    {
        // Nanti: $address = Address::findOrFail($id);
        
        // SEMENTARA (Static Dummy Data untuk testing tampilan Edit)
        $address = (object) [
            'id' => 1,
            'label' => 'Office',
            'recipient_name' => 'Alex Rivers',
            'phone' => '+62 812 3456 7890',
            'city' => 'Jakarta Selatan',
            'postal_code' => '12190',
            'full_address' => 'Gedung Cyber 2, Lt. 15, Kuningan',
            'is_default' => true
        ];

        return view('profile.address-form', compact('address'));
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