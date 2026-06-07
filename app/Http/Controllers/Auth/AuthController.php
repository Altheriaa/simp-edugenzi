<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLogin(): View
    {
        return view('pages.auth.signin', ['title' => 'Login']);
    }

    /**
     * Proses login user.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return redirect()->route('login')->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended($this->redirectBasedOnRole());
    }

    /**
     * Logout user.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Tentukan URL redirect berdasarkan role user.
     */
    private function redirectBasedOnRole(): string
    {
        return match(Auth::user()->role) {
            'admin'         => route('admin.dashboard'),
            'mentor'        => route('mentor.dashboard'),
            'peserta_didik' => route('peserta.dashboard'),
            default         => route('login'),
        };
    }
}
