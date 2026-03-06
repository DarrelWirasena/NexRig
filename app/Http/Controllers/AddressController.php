<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $title = 'My Addresses';
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();
        return view('profile.address', compact('addresses', 'title'));
    }

    public function create()
    {
        $title = 'Add New Address';
        return view('profile.address-form', compact('title'));
    }

    /**
     * Simpan Alamat Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'label'          => 'required|string|max:50',
            'recipient_name' => 'required|string|max:100',
            'phone'          => 'required|string|max:20',
            // Wilayah — pakai *_name karena yang dikirim adalah nama, bukan ID
            'province_name'  => 'required|string|max:100',
            'city_name'      => 'required|string|max:100',
            'district_name'  => 'required|string|max:100',
            'village_name'   => 'required|string|max:100',
            'postal_code'    => 'required|string|max:10',
            'full_address'   => 'required|string',
            // Koordinat opsional (bisa gagal geocode)
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
        ], [
            'province_name.required' => 'Provinsi wajib dipilih.',
            'city_name.required'     => 'Kota/Kabupaten wajib dipilih.',
            'district_name.required' => 'Kecamatan wajib dipilih.',
            'village_name.required'  => 'Kelurahan wajib dipilih.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $shouldBeDefault = $request->has('is_default');

        // Alamat pertama selalu jadi default
        if ($user->addresses()->doesntExist()) {
            $shouldBeDefault = true;
        }

        if ($shouldBeDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            'label'          => $request->label,
            'recipient_name' => $request->recipient_name,
            'phone'          => $request->phone,
            'province'       => $request->province_name,   // simpan nama, bukan ID
            'city'           => $request->city_name,
            'district'       => $request->district_name,
            'village'        => $request->village_name,
            'postal_code'    => $request->postal_code,
            'full_address'   => $request->full_address,
            'latitude'       => $request->latitude  ?: null,
            'longitude'      => $request->longitude ?: null,
            'is_default'     => $shouldBeDefault,
        ]);

        $origin = $request->input('origin');
        return redirect(route('address.index', $origin ? ['origin' => $origin] : []))
            ->with('success', 'Alamat berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $title = 'Edit Address';
        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $address = $user->addresses()->findOrFail($id);
        return view('profile.address-form', compact('address', 'title'));
    }

    /**
     * Update Alamat
     */
    public function update(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        $request->validate([
            'label'          => 'required|string|max:50',
            'recipient_name' => 'required|string|max:100',
            'phone'          => 'required|string|max:20',
            'province_name'  => 'required|string|max:100',
            'city_name'      => 'required|string|max:100',
            'district_name'  => 'required|string|max:100',
            'village_name'   => 'required|string|max:100',
            'postal_code'    => 'required|string|max:10',
            'full_address'   => 'required|string',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
        ], [
            'province_name.required' => 'Provinsi wajib dipilih.',
            'city_name.required'     => 'Kota/Kabupaten wajib dipilih.',
            'district_name.required' => 'Kecamatan wajib dipilih.',
            'village_name.required'  => 'Kelurahan wajib dipilih.',
        ]);

        $shouldBeDefault = $request->has('is_default');

        // Alamat default tidak boleh di-uncheck lewat form edit
        if ($address->is_default) {
            $shouldBeDefault = true;
        }

        if ($shouldBeDefault) {
            $user->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
        }

        $address->update([
            'label'          => $request->label,
            'recipient_name' => $request->recipient_name,
            'phone'          => $request->phone,
            'province'       => $request->province_name,
            'city'           => $request->city_name,
            'district'       => $request->district_name,
            'village'        => $request->village_name,
            'postal_code'    => $request->postal_code,
            'full_address'   => $request->full_address,
            'latitude'       => $request->latitude  ?: null,
            'longitude'      => $request->longitude ?: null,
            'is_default'     => $shouldBeDefault,
        ]);

        $origin = $request->input('origin');
        return redirect(route('address.index', $origin ? ['origin' => $origin] : []))
            ->with('success', 'Alamat berhasil diperbarui!');
    }

    public function destroy($id)
    {
        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        if ($address->is_default && $user->addresses()->count() > 1) {
            return back()->with('error', 'Gagal: Alamat Utama tidak bisa dihapus. Silakan jadikan alamat lain sebagai utama terlebih dahulu.');
        }

        $address->delete();

        // Fallback: jika tidak ada default tersisa, jadikan yang terbaru sebagai default
        if ($user->addresses()->exists() && !$user->addresses()->where('is_default', true)->exists()) {
            $user->addresses()->latest()->first()->update(['is_default' => true]);
        }

        return back()->with('success', 'Alamat berhasil dihapus.');
    }

    public function setDefault($id)
    {
        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        $user->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        if (request()->has('redirect_to') && request('redirect_to') == 'checkout') {
            return redirect()->route('checkout.index')->with('success', 'Alamat pengiriman diubah!');
        }

        return back()->with('success', 'Alamat utama berhasil diganti.');
    }
}
