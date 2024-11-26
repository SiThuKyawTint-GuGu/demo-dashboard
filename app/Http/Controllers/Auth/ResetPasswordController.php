<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Auth\ResetPasswordInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    protected $resetPasswordService;

    public function __construct(ResetPasswordInterface $resetPasswordService)
    {
        $this->resetPasswordService = $resetPasswordService;
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        $data = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $response = $this->resetPasswordService->resetPassword($data);

        if ($response == \Illuminate\Support\Facades\Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', trans($response));
        }

        return back()->withErrors(['email' => trans($response)]);
    }
}