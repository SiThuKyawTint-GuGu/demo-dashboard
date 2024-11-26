<?php

namespace App\Contracts\Auth;

interface SendPasswordResetEmailInterface
{
    public function sendResetLink(array $credentials);
}
