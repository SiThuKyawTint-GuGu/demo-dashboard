<?php

namespace App\Contracts\Auth;

interface ResetPasswordInterface
{
    public function resetPassword(array $data);
}
