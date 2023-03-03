<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AddAddressRequest;
use App\Services\AddressService;
use App\Services\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{

    public function __construct(private readonly AddressService $addressService)
    {
    }

    public function store(AddAddressRequest $request): JsonResponse
    {
        $response = $this->addressService->addAddress($request->only(["name", "address", "landmark", "city",
            "state", "pin_code", "email_id", "contact_number",]));

        if ($response['status']) {
            return response()->json(data: [
                "status" => true,
                "message" => "Address created Successfully",
                "data" => $response['address'],
            ], status: 201);
        }

        return response()->json(data: [
            "status" => false,
            "message" => "Something went wrong,please try again",
            "data" => null,
        ], status: 500);

    }

    public function destroy(string $addressId): JsonResponse
    {
        $response = $this->addressService->deleteAddress($addressId, Auth::guard('sanctum')->id());

        if ($response['status']) {
            return response()->json(data: [
                "status" => true,
                "message" => $response['message'],
                "data" => null,
            ]);
        }

        return response()->json(data: [
            "status" => false,
            "message" => "Something went wrong,please try again",
            "data" => null,
        ], status: 500);
    }

    public function index(): JsonResponse
    {
        $addresses = $this->addressService->fetchAddresses(Auth::guard('sanctum')->id());
        return response()->json(data: [
            "status" => true,
            "message" => "Addresses Fetched Successfully",
            "data" => $addresses,
        ]);
    }

    public function show(string $addressId): JsonResponse
    {
        $address = $this->addressService->fetchAddresses(Auth::id(), $addressId);
        return response()->json(data: [
            "status" => true,
            "message" => "Address Fetched Successfully",
            "data" => $address,
        ]);
    }
}
