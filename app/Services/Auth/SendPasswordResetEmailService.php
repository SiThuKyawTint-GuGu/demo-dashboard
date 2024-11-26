<?php

namespace App\Services\Auth;

use App\Contracts\Auth\SendPasswordResetEmailInterface;
use Illuminate\Support\Facades\Password;

class SendPasswordResetEmailService implements SendPasswordResetEmailInterface
{
    public function sendResetLink(array $credentials)
    {
        return Password::sendResetLink($credentials);
    }
}
