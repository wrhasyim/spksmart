<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Portal SPK Hubin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-white font-sans text-gray-900 overflow-hidden">
    <div class="flex min-h-screen">
        
        <div class="hidden lg:flex lg:w-1/2 bg-indigo-700 relative items-center justify-center">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-800 to-purple-700 opacity-90"></div>
            
            <div class="absolute -bottom-32 -left-40 w-96 h-96 rounded-full bg-white opacity-10 blur-2xl"></div>
            <div class="absolute -top-32 -right-40 w-96 h-96 rounded-full bg-indigo-400 opacity-20 blur-2xl"></div>

            <div class="relative z-10 text-center px-12">
                <svg class="w-24 h-24 mx-auto mb-6 text-indigo-200 drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h1 class="text-4xl font-extrabold text-white tracking-tight mb-4 drop-shadow-md">
                    Portal SPK Hubin
                </h1>
                <p class="text-lg text-indigo-100 max-w-md mx-auto leading-relaxed">
                    Sistem cerdas pendukung keputusan penempatan Prakerin. Mendistribusikan siswa secara adil, presisi, dan transparan menggunakan perhitungan metode SMART.
                </p>
            </div>
        </div>

        <div class="flex w-full lg:w-1/2 justify-center items-center bg-gray-50 px-6 py-12 lg:px-16 relative">
            
            <div class="w-full max-w-md bg-white p-10 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 z-10">
                
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-800 tracking-tight mb-2">Selamat Datang 👋</h2>
                    <p class="text-sm text-gray-500">Silakan masuk menggunakan akun Admin Anda.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus autocomplete="username" 
                                class="pl-10 w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-100 transition duration-200 outline-none" placeholder="Masukkan username">
                        </div>
                        <x-input-error :messages="$errors->get('username')" class="mt-2 text-red-500 text-sm font-medium" />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="current-password" 
                                class="pl-10 w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-100 transition duration-200 outline-none" placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm font-medium" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                            <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 transition duration-150">
                            <span class="ml-2 text-sm text-gray-600 group-hover:text-indigo-600 transition">Ingat Saya di perangkat ini</span>
                        </label>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3.5 px-4 rounded-xl shadow-lg shadow-indigo-600/30 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                            Masuk ke Sistem
                        </button>
                    </div>
                </form>
                
                <div class="mt-8 text-center text-sm">
                    <a href="{{ route('welcome') }}" class="font-medium text-gray-500 hover:text-indigo-600 transition duration-150 flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Beranda
                    </a>
                </div>

            </div>
        </div>
    </div>
</body>
</html>