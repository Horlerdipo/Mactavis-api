<?php

namespace App\Services;

use App\Models\Address;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AddressService
{
    public function addAddress(array $data): array
    {
        $response = [
            "status" => false,
            "address" => null,
        ];

        try {

            $response['address'] = Address::query()->create($data);
            $response['status'] = true;

        } catch (Exception $exception) {
            log_error(exception: $exception, abort: false);
        }
        return $response;
    }

    public function fetchAddresses($userId, $addressId = null): Collection|array|Address
    {

        $address = Address::query()
            ->when($addressId, function (Builder $query, string $addressId) {
                $query->where('id', $addressId);
            })
            ->where("user_id", $userId);

        return is_null($addressId) ? $address->get() : $address->first();
    }

    public function deleteAddress($addressId, $userId): array
    {

        $response = [
            "status" => false,
            "message" => "Something went wrong,please try again"
        ];

        try {

            $return = Address::query()->where(function ($query) use ($userId, $addressId) {
                $query->where("id", $addressId)->where("user_id", $userId);
            })->delete();

            if ($return) {

                $response['status'] = true;
                $response['message'] = "Address deleted";

            } else {
                $response['status'] = false;
                $response['message'] = "Unable to delete address";
            }

        } catch (Exception $exception) {
            log_error(exception: $exception, abort: false);
        }
        return $response;
    }

}
