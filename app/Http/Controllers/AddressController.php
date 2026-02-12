<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Menampilkan Daftar Alamat
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ambil data alamat, urutkan: Alamat Utama (Default) paling atas
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();

        // Path view: resources/views/profile/address.blade.php
        return view('profile.address', compact('addresses'));
    }

    /**
     * Menampilkan Form Tambah
     */
    public function create()
    {
        // Path view: resources/views/profile/address-form.blade.php
        return view('profile.address-form');
    }

    /**
     * Simpan Alamat Baru
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'label' => 'required|string|max:50',
            'recipient_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'full_address' => 'required|string',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 2. Cek Logika Default
        $shouldBeDefault = $request->has('is_default');

        // Jika user belum punya alamat sama sekali, alamat pertama WAJIB jadi default
        if ($user->addresses()->doesntExist()) {
            $shouldBeDefault = true;
        }

        // 3. Jika alamat ini akan jadi default, matikan status default di alamat lain
        if ($shouldBeDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        // 4. Simpan Data
        $user->addresses()->create([
            'label' => $request->label,
            'recipient_name' => $request->recipient_name,
            'phone' => $request->phone,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'full_address' => $request->full_address,
            'is_default' => $shouldBeDefault,
        ]);

        return redirect()->route('address.index')->with('success', 'Alamat berhasil ditambahkan!');
    }

    /**
     * Menampilkan Form Edit
     */
    public function edit($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Pastikan user hanya mengedit alamat miliknya sendiri
        $address = $user->addresses()->findOrFail($id);
        
        return view('profile.address-form', compact('address'));
    }

    /**
     * Update Alamat
     */
    public function update(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        // 1. Validasi
        $request->validate([
            'label' => 'required|string|max:50',
            'recipient_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'full_address' => 'required|string',
        ]);

        // 2. Cek Logika Default
        $shouldBeDefault = $request->has('is_default');

        // PROTEKSI: Jika alamat ini sebelumnya ADALAH default, user TIDAK BOLEH mematikannya lewat edit.
        // User harus men-set alamat LAIN jadi default, bukan mematikan satu-satunya default.
        if ($address->is_default) {
            $shouldBeDefault = true; 
        }

        // Jika user mengubah jadi default, matikan yang lain
        if ($shouldBeDefault) {
            $user->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
        }

        // 3. Update Data
        $address->update([
            'label' => $request->label,
            'recipient_name' => $request->recipient_name,
            'phone' => $request->phone,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'full_address' => $request->full_address,
            'is_default' => $shouldBeDefault,
        ]);

        return redirect()->route('address.index')->with('success', 'Alamat berhasil diperbarui!');
    }

    /**
     * Hapus Alamat
     */
    public function destroy($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        // PROTEKSI: Jangan biarkan user menghapus alamat Utama (Default)
        // Kecuali jika itu satu-satunya alamat yang dia punya (biar tidak stuck).
        if ($address->is_default && $user->addresses()->count() > 1) {
            return back()->with('error', 'Gagal: Alamat Utama tidak bisa dihapus. Silakan jadikan alamat lain sebagai utama terlebih dahulu.');
        }

        $address->delete();

        // FALLBACK: Jika user menghapus satu-satunya default (misal dipaksa sistem),
        // cek apakah masih ada alamat sisa? Jika ada, jadikan yang terbaru sebagai default.
        if ($user->addresses()->exists() && !$user->addresses()->where('is_default', true)->exists()) {
            $user->addresses()->latest()->first()->update(['is_default' => true]);
        }

        return back()->with('success', 'Alamat berhasil dihapus.');
    }

    /**
     * Method Tambahan: Set Default Cepat (Optional)
     * Berguna jika Anda ingin tombol "Jadikan Utama" di halaman list tanpa masuk ke form edit.
     * Route: Route::patch('/address/{id}/default', [AddressController::class, 'setDefault'])->name('address.set_default');
     */
    public function setDefault($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        // 1. Matikan semua default
        $user->addresses()->update(['is_default' => false]);

        // 2. Aktifkan default untuk ID yang dipilih
        $address->update(['is_default' => true]);

        // Cek jika request datang dari halaman checkout (opsional, untuk UX lebih bagus)
        if (request()->has('redirect_to') && request('redirect_to') == 'checkout') {
            return redirect()->route('checkout.index')->with('success', 'Alamat pengiriman diubah!');
        }

        return back()->with('success', 'Alamat utama berhasil diganti.');
    }
}