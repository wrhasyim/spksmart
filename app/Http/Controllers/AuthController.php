<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AppSetting;

class AuthController extends Controller
{
    public function showLogin()
    {
        $setting = AppSetting::first();
        return view('auth.login', compact('setting'));
    }

    public function login(Request $request)
    {
        // 1. Validasi Input Menggunakan Username
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // 2. Tangkap status tombol "Ingat Saya" (Checkbox)
        $remember = $request->boolean('remember');

        // 3. Lempar variabel $remember ke parameter kedua Auth::attempt()
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Mengambil nama asli admin untuk sapaan di dashboard
            $adminName = Auth::user()->name; 

            return redirect()->intended('dashboard')
                ->with('login_success', "Selamat Datang di Portal SPK Hubin, {$adminName}!");
        }

        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome');
    }
}