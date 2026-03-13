<?php
namespace App\Services;

use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;

class AddressService
{
public function getAllAddresses($userId)
{
return UserAddress::where('user_id', $userId)->orderBy('is_default', 'desc')->get();
}

public function createAddress($userId, $data)
{
$shouldBeDefault = $data['is_default'] ?? false;

if (UserAddress::where('user_id', $userId)->doesntExist()) {
$shouldBeDefault = true;
}

if ($shouldBeDefault) {
UserAddress::where('user_id', $userId)->update(['is_default' => false]);
}

$data['user_id'] = $userId;
$data['is_default'] = $shouldBeDefault;

return UserAddress::create($data);
}

public function updateAddress($userId, $id, $data)
{
$address = UserAddress::where('user_id', $userId)->findOrFail($id);

$shouldBeDefault = $data['is_default'] ?? $address->is_default;

if ($shouldBeDefault) {
UserAddress::where('user_id', $userId)->where('id', '!=', $id)->update(['is_default' => false]);
}

$data['is_default'] = $shouldBeDefault;
$address->update($data);

return $address;
}

public function deleteAddress($userId, $id)
{
$address = UserAddress::where('user_id', $userId)->findOrFail($id);

if ($address->is_default && UserAddress::where('user_id', $userId)->count() > 1) {
throw new \Exception('Cannot delete the default address. Set another address as default first.');
}

$address->delete();

if (UserAddress::where('user_id', $userId)->exists() && !UserAddress::where('user_id', $userId)->where('is_default', true)->exists()) {
UserAddress::where('user_id', $userId)->latest()->first()->update(['is_default' => true]);
}
}

public function setDefaultAddress($userId, $id)
{
$address = UserAddress::where('user_id', $userId)->findOrFail($id);

UserAddress::where('user_id', $userId)->update(['is_default' => false]);
$address->update(['is_default' => true]);
}
}