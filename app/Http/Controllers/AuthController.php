<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'username' => trim($request->input('username')),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))
                ->with('flash_success', 'Selamat datang kembali, ' . Auth::user()->username . '!');
        }

        return redirect()->back()
            ->withInput($request->only('username'))
            ->withErrors(['login' => 'Nama pengguna (username) atau kata sandi tidak valid.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('flash_success', 'Anda telah berhasil keluar dari sistem.');
    }
}
