<?php

namespace App\Http\Controllers\Site\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('site.auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            if (Auth::user()->status !== 'active') {
                Auth::logout();
                return back()->withErrors([
                    'email' => __('site.common.account_inactive'),
                ])->onlyInput('email');
            }

            Auth::user()->updateLoginInfo($request);

            $user = Auth::user();
            
            if ($user->type === 'admin' || $user->type === 'screener' || $user->type === 'operator') {
                return redirect()->intended(route('portal.dashboard.index'));
            }

            return redirect()->intended(route('site.home.index'));
        }

        return back()->withErrors([
            'email' => __('site.common.login_failed'),
        ])->onlyInput('email');
    }
} 