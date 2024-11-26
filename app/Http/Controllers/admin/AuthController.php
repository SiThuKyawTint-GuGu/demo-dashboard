<?php

namespace App\Http\Controllers\admin;

use App\Contracts\Auth\LoginInterface;
use App\Contracts\Auth\RegisterInterface;
use App\Contracts\MFA\MfaInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $loginService;
    protected $registerService;
    protected $mfaService;

    public function __construct(LoginInterface $loginService, RegisterInterface $registerService, MfaInterface $mfaService,)
    {
        $this->loginService = $loginService;
        $this->registerService = $registerService;
        $this->mfaService = $mfaService;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if ($this->loginService->login($request)) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('home');
            }

            $this->mfaService->sendMfaCode($user);
            return redirect()->route('mfa.form');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }


    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->all();
        $this->registerService->register($data); 

        return redirect()->route('home'); 
    }

    public function showMFAForm()
    {
        return view('auth.mfa');
    }

    public function verifyMFA(Request $request)
    {
        $request->validate(['code' => 'required']);

        $user = Auth::user();
        if ($this->mfaService->verifyMfaCode($user, $request->code)) {
            $user->mfa_code = null;
            $user->save();

            return redirect()->route('home');
        }

        return back()->withErrors(['code' => 'Invalid or expired MFA code.']);
    }

    public function resendMFA()
    {
        $user = Auth::user();
        $this->mfaService->resendMfaCode($user);

        return redirect()->route('mfa.form');
    }

    public function logout()
    {
        $this->loginService->logout();
        return redirect()->route('login');
    }
}
