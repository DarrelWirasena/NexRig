<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Services\AddressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends ApiController
{
    protected AddressService $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    public function index(): JsonResponse
    {
        $addresses = $this->addressService->getAllAddresses(auth()->id());

        return $this->successResponse($addresses, 'Addresses retrieved successfully');
    }

    public function store(Request $request): JsonResponse
    {
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
        ]);

        $address = $this->addressService->createAddress(auth()->id(), $request->all());

        return $this->createdResponse($address, 'Address created successfully');
    }

    public function update(Request $request, int $id): JsonResponse
    {
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
        ]);

        $address = $this->addressService->updateAddress(auth()->id(), $id, $request->all());

        return $this->successResponse($address, 'Address updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->addressService->deleteAddress(auth()->id(), $id);

        return $this->successResponse(null, 'Address deleted successfully');
    }

    public function setDefault(int $id): JsonResponse
    {
        $this->addressService->setDefaultAddress(auth()->id(), $id);

        return $this->successResponse(null, 'Default address set successfully');
    }
}