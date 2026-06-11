<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Hubin') - SPK Prakerin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <nav class="bg-white shadow-sm border-b border-gray-200 mb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex space-x-8">
                    <div class="font-bold text-xl text-gray-800 flex items-center">Panel Hubin</div>
                    <div class="hidden md:flex space-x-6 items-center">
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-indigo-600 font-bold border-b-2 border-indigo-600 h-16 flex items-center' : 'text-gray-600 hover:text-indigo-600' }}">Rekomendasi SPK</a>
                        <a href="{{ route('admin.academic-years.index') }}" class="{{ request()->routeIs('admin.academic-years.*') ? 'text-indigo-600 font-bold border-b-2 border-indigo-600 h-16 flex items-center' : 'text-gray-600 hover:text-indigo-600' }}">Tahun Ajaran</a>
                        <a href="{{ route('admin.companies.index') }}" class="{{ request()->routeIs('admin.companies.*') ? 'text-indigo-600 font-bold border-b-2 border-indigo-600 h-16 flex items-center' : 'text-gray-600 hover:text-indigo-600' }}">Perusahaan Mitra</a>
                        <a href="{{ route('admin.students.index') }}" class="{{ request()->routeIs('admin.students.*') ? 'text-indigo-600 font-bold border-b-2 border-indigo-600 h-16 flex items-center' : 'text-gray-600 hover:text-indigo-600' }}">Data Siswa & Nilai</a>
                    </div>
                </div>
                <div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-red-600 hover:underline font-semibold">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

</body>
</html>