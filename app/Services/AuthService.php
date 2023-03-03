<?php

namespace App\Services;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{

    public function registerUser(array $data): Model|Builder
    {
        $data['referred_by'] = $this->getUserDetailsWithPhoneNumber($data["referred_by"])->id;
        $data["password"] = Hash::make($data['password']);
        return User::query()->create($data);
    }

    /**
     * @throws ValidationException
     */
    public function loginUser(array $data){
        $user = User::query()->where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data["password"], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken($user->email)->plainTextToken;
    }

    public function getUserDetailsWithPhoneNumber($phoneNumber): Model|Builder|null
    {
        return User::query()->where("phone_number", $phoneNumber)->first();
    }
}
