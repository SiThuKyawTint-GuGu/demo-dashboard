<?php

namespace App\Services\Auth;

use App\Contracts\Auth\ResetPasswordInterface;
use Illuminate\Support\Facades\Password;

class ResetPasswordService implements ResetPasswordInterface
{
    public function resetPassword(array $data)
    {
        return Password::reset($data, function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        });
    }
}
