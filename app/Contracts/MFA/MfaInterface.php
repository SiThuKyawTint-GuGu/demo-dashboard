<?php

namespace App\Contracts\MFA;

use App\Models\User;

interface MfaInterface
{
    public function sendMfaCode(User $user): void;
    public function verifyMfaCode(User $user, string $code): bool;
    public function resendMfaCode(User $user): void;
}