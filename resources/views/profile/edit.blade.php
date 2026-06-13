@extends('layouts.hubin')

@section('title', 'Profil Akun')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <div class="mb-6 border-b border-gray-100 pb-4">
        <h1 class="text-2xl font-extrabold text-gray-900">⚙️ Pengaturan Profil</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola informasi data diri dan kredensial akun Anda.</p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-4 rounded-xl shadow-sm font-medium text-sm flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Berhasil: Informasi profil Anda telah diperbarui.
        </div>
    @endif

    <div class="bg-white p-8 shadow-sm rounded-2xl border border-gray-100">
        <header class="mb-6">
            <h2 class="text-lg font-bold text-gray-900">Informasi Dasar</h2>
            <p class="mt-1 text-sm text-gray-500">Perbarui nama tampilan dan alamat email akun Anda.</p>
        </header>

        <form method="post" action="{{ route('profile.update') }}" class="space-y-6 max-w-xl">
            @csrf
            @method('patch')

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                @error('name') 
                    <span class="text-red-500 text-xs font-medium mt-1 flex items-center mt-2"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $message }}</span> 
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Email Akses</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                @error('email') 
                    <span class="text-red-500 text-xs font-medium mt-1 flex items-center mt-2"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $message }}</span> 
                @enderror
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl shadow-md hover:bg-indigo-700 font-bold transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection