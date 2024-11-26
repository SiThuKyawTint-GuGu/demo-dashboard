<?php

namespace App\Services\MFA;

use App\Contracts\MFA\MfaInterface;
use App\Models\User;
use App\Mail\MfaCode;
use Illuminate\Support\Facades\Mail;

class MfaService implements MfaInterface
{
    public function sendMfaCode(User $user): void
    {
        $mfaCode = str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT); 

        $user->mfa_code = $mfaCode;
        $user->mfa_expires_at = now()->addMinutes(10);
        $user->save();

        Mail::to($user->email)->send(new MfaCode($mfaCode));
    }

    public function verifyMfaCode(User $user, string $code): bool
    {
        return $user->mfa_code === $code && now()->lessThan($user->mfa_expires_at);
    }

    public function resendMfaCode(User $user): void
    {
        $this->sendMfaCode($user);
    }
}

