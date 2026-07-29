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
<body class="h-full bg-slate-50 text-gray-900 antialiased">
    <div class="min-h-full flex flex-col justify-between">
        <div>
            <!-- Top Navbar Header -->
            <header class="bg-emerald-800 text-white shadow-md sticky top-0 z-50">
                <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3.5 sm:px-6">
                    <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-2.5 font-bold tracking-tight">
                        <div class="rounded-lg bg-emerald-700/80 p-2 shadow-inner border border-emerald-600">
                            <svg class="h-6 w-6 text-emerald-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h6m-6 4h6m-6 4h6" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-base font-bold block leading-tight">SIPADES</span>
                            <span class="text-[11px] text-emerald-200 font-medium block">Desa Karduluk</span>
                        </div>
                    </a>

                    <!-- Desktop Navigation Links -->
                    <nav class="hidden md:flex items-center gap-1 text-sm font-medium">
                        @auth
                            <a href="{{ route('portal.dashboard') }}"
                                @class([
                                    'px-3 py-2 rounded-lg transition',
                                    'bg-emerald-900/60 text-white font-semibold' => request()->routeIs('portal.dashboard'),
                                    'text-emerald-100 hover:bg-emerald-700/60 hover:text-white' => !request()->routeIs('portal.dashboard'),
                                ])>
                                Beranda
                            </a>

                            <a href="{{ route('portal.pengajuan.buat') }}"
                                @class([
                                    'px-3 py-2 rounded-lg transition',
                                    'bg-emerald-900/60 text-white font-semibold' => request()->routeIs('portal.pengajuan.buat'),
                                    'text-emerald-100 hover:bg-emerald-700/60 hover:text-white' => !request()->routeIs('portal.pengajuan.buat'),
                                ])>
                                + Ajukan Surat
                            </a>

                            <a href="{{ route('portal.pengajuan.index') }}"
                                @class([
                                    'px-3 py-2 rounded-lg transition',
                                    'bg-emerald-900/60 text-white font-semibold' => request()->routeIs('portal.pengajuan.index') || request()->routeIs('portal.pengajuan.status'),
                                    'text-emerald-100 hover:bg-emerald-700/60 hover:text-white' => !request()->routeIs('portal.pengajuan.index') && !request()->routeIs('portal.pengajuan.status'),
                                ])>
                                Pengajuan Saya
                            </a>

                            <a href="{{ route('portal.surat-terbit.index') }}"
                                @class([
                                    'px-3 py-2 rounded-lg transition',
                                    'bg-emerald-900/60 text-white font-semibold' => request()->routeIs('portal.surat-terbit.index'),
                                    'text-emerald-100 hover:bg-emerald-700/60 hover:text-white' => !request()->routeIs('portal.surat-terbit.index'),
                                ])>
                                Surat Terbit
                            </a>

                            <a href="{{ route('portal.profil') }}"
                                @class([
                                    'px-3 py-2 rounded-lg transition',
                                    'bg-emerald-900/60 text-white font-semibold' => request()->routeIs('portal.profil'),
                                    'text-emerald-100 hover:bg-emerald-700/60 hover:text-white' => !request()->routeIs('portal.profil'),
                                ])>
                                Profil Saya
                            </a>
                        @endauth
                    </nav>

                    <!-- User Badge & Logout -->
                    <div class="flex items-center gap-3">
                        @auth
                            <a href="{{ route('portal.profil') }}" class="hidden sm:flex items-center gap-2 text-xs text-emerald-100 bg-emerald-900/50 px-3 py-1.5 rounded-full border border-emerald-700 hover:border-emerald-500 transition">
                                <div class="h-5 w-5 rounded-full bg-emerald-600 text-white font-bold text-[10px] flex items-center justify-center">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="font-medium truncate max-w-[120px]">{{ Auth::user()->name }}</span>
                            </a>

                            <form method="POST" action="{{ route('portal.logout') }}">
                                @csrf
                                <button type="submit" class="rounded-lg bg-emerald-900/80 px-3 py-1.5 text-xs font-semibold text-emerald-200 hover:bg-red-600 hover:text-white transition shadow-sm">
                                    Keluar
                                </button>
                            </form>
                        @else
                            <a href="{{ route('portal.login') }}" class="text-sm font-semibold text-emerald-100 hover:text-white">Masuk</a>
                            <a href="{{ route('registrasi') }}" class="rounded-lg bg-emerald-600 px-3.5 py-1.5 text-sm font-semibold text-white hover:bg-emerald-500 transition shadow-sm">Daftar Akun</a>
                        @endauth
                    </div>
                </div>

                <!-- Mobile Menu Sub-bar -->
                @auth
                    <div class="flex md:hidden overflow-x-auto border-t border-emerald-700/60 px-4 py-2 text-xs font-medium space-x-2 scrollbar-none">
                        <a href="{{ route('portal.dashboard') }}" class="px-2.5 py-1 rounded-md {{ request()->routeIs('portal.dashboard') ? 'bg-emerald-900 text-white font-bold' : 'text-emerald-100' }}">Beranda</a>
                        <a href="{{ route('portal.pengajuan.buat') }}" class="px-2.5 py-1 rounded-md {{ request()->routeIs('portal.pengajuan.buat') ? 'bg-emerald-900 text-white font-bold' : 'text-emerald-100' }}">+ Ajukan Surat</a>
                        <a href="{{ route('portal.pengajuan.index') }}" class="px-2.5 py-1 rounded-md {{ request()->routeIs('portal.pengajuan.index') ? 'bg-emerald-900 text-white font-bold' : 'text-emerald-100' }}">Pengajuan Saya</a>
                        <a href="{{ route('portal.surat-terbit.index') }}" class="px-2.5 py-1 rounded-md {{ request()->routeIs('portal.surat-terbit.index') ? 'bg-emerald-900 text-white font-bold' : 'text-emerald-100' }}">Surat Terbit</a>
                        <a href="{{ route('portal.profil') }}" class="px-2.5 py-1 rounded-md {{ request()->routeIs('portal.profil') ? 'bg-emerald-900 text-white font-bold' : 'text-emerald-100' }}">Profil Saya</a>
                    </div>
                @endauth
            </header>

            <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
                @if (session('status'))
                    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm flex items-center gap-2">
                        <span>✓</span>
                        <div>{{ session('status') }}</div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm flex items-center gap-2">
                        <span>⚠️</span>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>

        <footer class="bg-white border-t border-gray-200 py-6 text-center text-xs text-gray-500 mt-12">
            <div class="mx-auto max-w-6xl px-4">
                &copy; {{ now()->year }} Pemerintah Desa Karduluk, Kec. Pragaan, Kab. Sumenep. Sistem Informasi Pelayanan Desa (SIPADES).
            </div>
        </footer>
    </div>

    @livewireScripts
</body>
</html>
