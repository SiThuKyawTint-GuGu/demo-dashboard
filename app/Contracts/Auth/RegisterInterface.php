<?php

namespace App\Contracts\Auth;

interface RegisterInterface
{
    public function register(array $data);
}