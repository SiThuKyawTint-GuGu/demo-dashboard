<?php

namespace App\Services\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Contracts\Auth\LoginInterface;

class LoginService implements LoginInterface
{
    public function login(Request $request): bool
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        return Auth::attempt($credentials);
    }

    public function logout(): void
    {
        Auth::logout();
    }
}