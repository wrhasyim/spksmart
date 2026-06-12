<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Perusahaan - SPK Prakerin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased py-10">

    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('admin.companies.index') }}" class="text-indigo-600 hover:underline font-medium">&larr; Kembali ke Daftar Perusahaan</a>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-2">Form Input Master Perusahaan</h2>
            <p class="text-sm text-gray-500 mb-6">Tambahkan data profil industri. Untuk pengaturan kuota dan syarat nilai, lakukan di menu Gelombang Lowongan.</p>

            <form action="{{ route('admin.companies.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700">Nama Perusahaan / Industri <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: PT. Toyota Motor Manufacturing" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700">Nomor Telepon (Opsional)</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 021-1234567" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700">Email Utama (Opsional)</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Contoh: hrd@toyota.co.id" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700">Alamat Lengkap Perusahaan</label>
                        <textarea name="address" rows="3" placeholder="Masukkan alamat lengkap industri..." class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('address') }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end border-t border-gray-200 pt-5">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded shadow hover:bg-indigo-700 font-bold transition duration-150">
                        Simpan Data Perusahaan
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>