@extends('layouts.hubin')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 py-4">
    
    <div class="border-b border-gray-100 pb-4">
        <h1 class="text-2xl font-extrabold text-gray-900">⚙️ Pengaturan Akun & Akses</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola informasi identitas, kredensial login, dan keamanan kata sandi Anda.</p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-4 rounded-xl shadow-sm font-bold text-sm flex items-center animate-fade-in mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Berhasil: Informasi profil Anda telah diperbarui.
        </div>
    @endif

    @if (session('status') === 'password-updated')
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-4 rounded-xl shadow-sm font-bold text-sm flex items-center animate-fade-in mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Berhasil: Kata sandi berhasil diubah.
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <div class="bg-white p-8 shadow-sm rounded-2xl border border-gray-100 h-fit">
            <header class="mb-6">
                <h2 class="text-lg font-bold text-gray-900">Informasi Identitas</h2>
                <p class="mt-1 text-xs text-gray-500">Perbarui nama lengkap dan username akses Anda.</p>
            </header>

            <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
                @csrf
                @method('patch')

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                    @error('name') 
                        <p class="text-red-500 text-xs font-bold mt-1.5 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}
                        </p> 
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Username Login</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                    @error('username') 
                        <p class="text-red-500 text-xs font-bold mt-1.5 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}
                        </p> 
                    @enderror
                </div>

                <div class="flex items-center gap-4 pt-2 border-t border-gray-100">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl shadow-md font-bold transition text-sm">
                        Simpan Perubahan Identitas
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white p-8 shadow-sm rounded-2xl border border-gray-100 h-fit">
            <header class="mb-6">
                <h2 class="text-lg font-bold text-gray-900">Keamanan Akses (Password)</h2>
                <p class="mt-1 text-xs text-gray-500">Ubah kata sandi akun secara berkala untuk keamanan sistem.</p>
            </header>

            <form method="post" action="{{ route('password.update') }}" class="space-y-5">
                @csrf
                @method('put')

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                    @error('current_password', 'updatePassword') 
                        <p class="text-red-500 text-xs font-bold mt-1.5 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}
                        </p> 
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kata Sandi Baru</label>
                    <input type="password" name="password" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                    @error('password', 'updatePassword') 
                        <p class="text-red-500 text-xs font-bold mt-1.5 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}
                        </p> 
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="password_confirmation" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                    @error('password_confirmation', 'updatePassword') 
                        <p class="text-red-500 text-xs font-bold mt-1.5 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}
                        </p> 
                    @enderror
                </div>

                <div class="flex items-center gap-4 pt-2 border-t border-gray-100">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl shadow-md font-bold transition text-sm">
                        Perbarui Password
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection