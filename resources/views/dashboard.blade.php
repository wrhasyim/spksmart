@extends('layouts.hubin')

@section('title', 'Dasbor Utama')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Halo, {{ Auth::user()->name ?? 'Administrator' }}! 👋</h1>
            <p class="mt-2 text-gray-500 font-medium">Selamat datang di Sistem Pendukung Keputusan Penempatan Industri (SPK SMART). Apa yang ingin Anda kelola hari ini?</p>
            
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('admin.placements.index') }}" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md transition flex items-center gap-2 text-sm">
                    ⚙️ Mulai Proses SPK
                </a>
                <a href="{{ route('admin.students.index') }}" class="px-6 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 font-bold border border-gray-200 rounded-xl transition flex items-center gap-2 text-sm">
                    👥 Kelola Data Siswa
                </a>
            </div>
        </div>
        
        <div class="hidden md:block w-48 h-48 bg-indigo-50 rounded-full opacity-50 relative z-0 flex-shrink-0 flex items-center justify-center">
             <span class="text-6xl">🏢</span>
        </div>
    </div>
</div>
@endsection