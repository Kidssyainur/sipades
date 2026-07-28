@props(['title' => 'Portal Pelayanan Desa Karduluk'])

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-gray-50 text-gray-900 antialiased">
    <div class="min-h-full">
        <header class="bg-emerald-700 text-white shadow">
            <div class="mx-auto flex max-w-4xl items-center justify-between px-4 py-4">
                <a href="{{ url('/') }}" class="flex items-center gap-2 font-semibold">
                    <span class="text-lg">Pelayanan Desa Karduluk</span>
                </a>
                <nav class="flex items-center gap-4 text-sm">
                    @auth
                        <a href="{{ route('portal.dashboard') }}" class="hover:underline">Dashboard</a>
                        <form method="POST" action="{{ route('portal.logout') }}">
                            @csrf
                            <button type="submit" class="rounded bg-emerald-600 px-3 py-1 hover:bg-emerald-500">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('portal.login') }}" class="hover:underline">Masuk</a>
                        <a href="{{ route('registrasi') }}" class="rounded bg-emerald-600 px-3 py-1 hover:bg-emerald-500">Daftar</a>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-4xl px-4 py-8">
            @if (session('status'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </main>

        <footer class="mx-auto max-w-4xl px-4 py-8 text-center text-xs text-gray-400">
            &copy; {{ now()->year }} Pemerintah Desa Karduluk. Sistem Informasi Pelayanan Desa.
        </footer>
    </div>

    @livewireScripts
</body>
</html>
