<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPK Prakerin - {{ $setting->nama_sekolah ?? 'SMK' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-800">
    <nav class="bg-white shadow-sm border-b px-6 py-4 flex justify-between items-center">
        <div class="font-bold text-xl text-indigo-700 flex items-center gap-3">
            @if(!empty($setting->logo_path))
                <img src="{{ asset('storage/' . $setting->logo_path) }}" alt="Logo {{ $setting->nama_sekolah }}" class="h-10 w-auto object-contain">
            @else
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            @endif
            <span class="tracking-tight">{{ $setting->nama_sekolah ?? 'Sistem SPK Prakerin' }}</span>
        </div>
        
        <div>
            @auth
                <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-indigo-600 px-4">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-md transition duration-150">Masuk (Hubin)</a>
            @endauth
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900 mb-4">
            Sistem Pendukung Keputusan <br> <span class="text-indigo-600">Penempatan Prakerin</span>
        </h1>
        <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
            Membantu Hubungan Industri (Hubin) dalam mendistribusikan siswa secara transparan, akurat, dan efisien menggunakan perhitungan Metode SMART.
        </p>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-16 mb-20">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Cek Penempatan Mandiri Siswa</h2>
            <p class="text-center text-gray-500 mb-6 text-sm">Masukkan NISN Anda untuk mengecek status penempatan Praktik Kerja Lapangan (PKL).</p>
            
            <form action="{{ route('track_nisn') }}" method="POST" class="flex gap-3">
                @csrf
                <input type="text" name="nisn" placeholder="Ketik NISN di sini..." required class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-lg py-3 px-4">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-md transition duration-150 shadow-md">
                    Lacak
                </button>
            </form>

            <div class="mt-6">
                @if(session('tracker_success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-md text-green-800 font-medium">
                        ✅ {{ session('tracker_success') }}
                    </div>
                @endif

                @if(session('tracker_info'))
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-md text-blue-800 font-medium">
                        ⏳ {{ session('tracker_info') }}
                    </div>
                @endif

                @if(session('tracker_error'))
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md text-red-800 font-medium">
                        ❌ {{ session('tracker_error') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>