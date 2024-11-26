<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Auth\SendPasswordResetEmailInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    protected $passwordResetEmailService;

    public function __construct(SendPasswordResetEmailInterface $passwordResetEmailService)
    {
        $this->passwordResetEmailService = $passwordResetEmailService;
    }

    public function showLinkRequestForm()
    {
        return view('auth.passwords.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $response = $this->passwordResetEmailService->sendResetLink($request->only('email'));

        if ($response === \Illuminate\Support\Facades\Password::RESET_LINK_SENT) {
            return back()->with('status', trans($response));
        }

        return back()->withErrors(['email' => trans($response)]);
    }
}