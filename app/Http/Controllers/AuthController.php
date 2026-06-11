<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman form login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'], // Menggunakan username
            'password' => ['required'],
        ]);

        // Auth::attempt akan mencocokkan field username dan password secara otomatis
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard'); 
        }

        return back()->withErrors([
            'username' => 'Kombinasi username dan password tidak sesuai atau Anda tidak memiliki akses.',
        ])->onlyInput('username');
    }

    /**
     * Proses logout sistem
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}