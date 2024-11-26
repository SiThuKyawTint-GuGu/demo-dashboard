<?php

namespace App\Contracts\Auth;

use Illuminate\Http\Request;

interface LoginInterface
{
    public function login(Request $request): bool;
    public function logout(): void;
}
