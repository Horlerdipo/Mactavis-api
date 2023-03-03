<?php

namespace App\Services;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CookieService
{

    public function registerNewUser(array $data): Model|Builder
    {
        $data['role'] = UserType::CUSTOMER;
        $data['referred_by'] = $this->getUserDetailsWithPhoneNumber($data["sponsor"])->id;
        return User::query()->create($data);
    }

    public function getUserDetailsWithPhoneNumber($phoneNumber): Model|Builder|null
    {
        return User::query()->where("phone_number", $phoneNumber)->first();
    }
}
