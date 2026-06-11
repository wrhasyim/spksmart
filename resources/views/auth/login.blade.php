<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Hubin - SPK Prakerin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
        <div class="mb-8 text-center">
            <h2 class="text-2xl font-bold text-gray-900">Panel Login Hubin</h2>
            <p class="text-sm text-gray-600 mt-1">Sistem Pendukung Keputusan Prakerin</p>
        </div>

        @if($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-sm" role="alert">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="username">Username</label>
                <input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                       id="username" type="text" name="username" value="{{ old('username') }}" required autofocus placeholder="adminhubin">
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700" for="password">Password</label>
                <input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                       id="password" type="password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded-md shadow hover:bg-indigo-700 transition duration-150">
                Masuk (Sign In)
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-gray-500">
            <a href="/" class="hover:underline">&larr; Kembali ke Beranda</a>
        </div>
    </div>

</body>
</html>