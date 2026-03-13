<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    private array $validationRules = [
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
    ];

    private array $validationMessages = [
        'province_name.required' => 'Provinsi wajib dipilih.',
        'city_name.required'     => 'Kota/Kabupaten wajib dipilih.',
        'district_name.required' => 'Kecamatan wajib dipilih.',
        'village_name.required'  => 'Kelurahan wajib dipilih.',
    ];

    public function index()
    {
        /** @var \App\Models\User $user */
        $user      = Auth::user();
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();

        return $this->view('profile.address', 'My Addresses', compact('addresses'));
    }

    public function create()
    {
        return $this->view('profile.address-form', 'Add New Address');
    }

    public function store(Request $request)
    {
        $request->validate($this->validationRules, $this->validationMessages);

        /** @var \App\Models\User $user */
        $user            = Auth::user();
        $shouldBeDefault = $request->has('is_default') || $user->addresses()->doesntExist();

        if ($shouldBeDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            'label'          => $request->label,
            'recipient_name' => $request->recipient_name,
            'phone'          => $request->phone,
            'province'       => $request->province_name,
            'city'           => $request->city_name,
            'district'       => $request->district_name,
            'village'        => $request->village_name,
            'postal_code'    => $request->postal_code,
            'full_address'   => $request->full_address,
            'latitude'       => $request->latitude ?: null,
            'longitude'      => $request->longitude ?: null,
            'is_default'     => $shouldBeDefault,
        ]);

        $origin = $request->input('origin');
        return redirect(route('address.index', $origin ? ['origin' => $origin] : []))
            ->with('success', 'Alamat berhasil ditambahkan!');
    }

    public function edit(int $id)
    {
        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        return $this->view('profile.address-form', 'Edit Address', compact('address'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate($this->validationRules, $this->validationMessages);

        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        // Alamat default tidak boleh di-uncheck
        $shouldBeDefault = $address->is_default ? true : $request->has('is_default');

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
            'latitude'       => $request->latitude ?: null,
            'longitude'      => $request->longitude ?: null,
            'is_default'     => $shouldBeDefault,
        ]);

        $origin = $request->input('origin');
        return redirect(route('address.index', $origin ? ['origin' => $origin] : []))
            ->with('success', 'Alamat berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        if ($address->is_default && $user->addresses()->count() > 1) {
            return $this->redirectError('Alamat Utama tidak bisa dihapus. Silakan jadikan alamat lain sebagai utama terlebih dahulu.');
        }

        $address->delete();

        // Fallback: jadikan yang terbaru sebagai default jika tidak ada default
        if ($user->addresses()->exists() && !$user->addresses()->where('is_default', true)->exists()) {
            $user->addresses()->latest()->first()->update(['is_default' => true]);
        }

        return back()->with('success', 'Alamat berhasil dihapus.');
    }

    public function setDefault(int $id)
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