<?php

namespace App\Services\Auth;

use App\Contracts\Auth\RegisterInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterService implements RegisterInterface
{
    public function register(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}