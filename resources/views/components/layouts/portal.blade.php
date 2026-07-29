@props(['title' => 'Portal Pelayanan Desa Karduluk'])

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — SIPADES Desa Karduluk</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-800 antialiased selection:bg-emerald-500 selection:text-white">
    <div class="min-h-full flex flex-col justify-between">
        <div>
            <!-- Premium Glassmorphism Navbar Header -->
            <header class="sticky top-0 z-50 bg-slate-900/95 backdrop-blur-md border-b border-slate-800 text-white shadow-xl">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6">
                    <!-- Brand Logo -->
                    <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-3 group">
                        <div class="relative flex items-center justify-center h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-700 text-white shadow-lg shadow-emerald-900/40 group-hover:scale-105 transition-transform duration-300">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h6m-6 4h6m-6 4h6" />
                            </svg>
                            <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500 border-2 border-slate-900"></span>
                            </span>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-base font-extrabold tracking-tight text-white group-hover:text-emerald-400 transition-colors">SIPADES</span>
                                <span class="rounded bg-emerald-500/20 px-1.5 py-0.5 text-[10px] font-bold text-emerald-400 border border-emerald-500/30">WARGA</span>
                            </div>
                            <span class="text-[11px] text-slate-400 font-medium block">Pemerintah Desa Karduluk</span>
                        </div>
                    </a>

                    <!-- Desktop Navigation Pills -->
                    <nav class="hidden md:flex items-center gap-1 text-sm font-semibold">
                        @auth
                            <a href="{{ route('portal.dashboard') }}"
                                @class([
                                    'px-4 py-2 rounded-xl transition-all duration-200 flex items-center gap-2',
                                    'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md shadow-emerald-950/50' => request()->routeIs('portal.dashboard'),
                                    'text-slate-300 hover:bg-slate-800 hover:text-white' => !request()->routeIs('portal.dashboard'),
                                ])>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Beranda
                            </a>

                            <a href="{{ route('portal.pengajuan.buat') }}"
                                @class([
                                    'px-4 py-2 rounded-xl transition-all duration-200 flex items-center gap-2',
                                    'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md shadow-emerald-950/50' => request()->routeIs('portal.pengajuan.buat'),
                                    'text-slate-300 hover:bg-slate-800 hover:text-white' => !request()->routeIs('portal.pengajuan.buat'),
                                ])>
                                <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                Ajukan Surat
                            </a>

                            <a href="{{ route('portal.pengajuan.index') }}"
                                @class([
                                    'px-4 py-2 rounded-xl transition-all duration-200 flex items-center gap-2',
                                    'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md shadow-emerald-950/50' => request()->routeIs('portal.pengajuan.index') || request()->routeIs('portal.pengajuan.status'),
                                    'text-slate-300 hover:bg-slate-800 hover:text-white' => !request()->routeIs('portal.pengajuan.index') && !request()->routeIs('portal.pengajuan.status'),
                                ])>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Pengajuan Saya
                            </a>

                            <a href="{{ route('portal.surat-terbit.index') }}"
                                @class([
                                    'px-4 py-2 rounded-xl transition-all duration-200 flex items-center gap-2',
                                    'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md shadow-emerald-950/50' => request()->routeIs('portal.surat-terbit.index'),
                                    'text-slate-300 hover:bg-slate-800 hover:text-white' => !request()->routeIs('portal.surat-terbit.index'),
                                ])>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                Surat Terbit
                            </a>

                            <a href="{{ route('portal.profil') }}"
                                @class([
                                    'px-4 py-2 rounded-xl transition-all duration-200 flex items-center gap-2',
                                    'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md shadow-emerald-950/50' => request()->routeIs('portal.profil'),
                                    'text-slate-300 hover:bg-slate-800 hover:text-white' => !request()->routeIs('portal.profil'),
                                ])>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profil Saya
                            </a>
                        @endauth
                    </nav>

                    <!-- User Avatar & Logout Action -->
                    <div class="flex items-center gap-3">
                        @auth
                            <a href="{{ route('portal.profil') }}" class="hidden sm:flex items-center gap-2.5 bg-slate-800/90 hover:bg-slate-800 border border-slate-700/80 px-3.5 py-1.5 rounded-full text-xs font-semibold text-slate-200 transition shadow-inner">
                                <div class="h-6 w-6 rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 text-slate-950 font-extrabold flex items-center justify-center shadow">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="max-w-[130px] truncate text-slate-200">{{ Auth::user()->name }}</span>
                            </a>

                            <form method="POST" action="{{ route('portal.logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-1.5 rounded-xl bg-slate-800 border border-slate-700 px-3.5 py-2 text-xs font-bold text-slate-300 hover:bg-rose-600 hover:text-white hover:border-rose-500 transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('portal.login') }}" class="text-sm font-semibold text-slate-300 hover:text-white px-3 py-2">Masuk</a>
                            <a href="{{ route('registrasi') }}" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2 text-sm font-bold text-white shadow-lg shadow-emerald-900/30 hover:opacity-95 transition">Daftar Akun</a>
                        @endauth
                    </div>
                </div>

                <!-- Mobile Menu Sub-bar -->
                @auth
                    <div class="flex md:hidden overflow-x-auto border-t border-slate-800/80 px-4 py-2 text-xs font-semibold space-x-2 scrollbar-none bg-slate-950/80">
                        <a href="{{ route('portal.dashboard') }}" class="px-3 py-1.5 rounded-lg whitespace-nowrap {{ request()->routeIs('portal.dashboard') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800' }}">Beranda</a>
                        <a href="{{ route('portal.pengajuan.buat') }}" class="px-3 py-1.5 rounded-lg whitespace-nowrap {{ request()->routeIs('portal.pengajuan.buat') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800' }}">+ Ajukan Surat</a>
                        <a href="{{ route('portal.pengajuan.index') }}" class="px-3 py-1.5 rounded-lg whitespace-nowrap {{ request()->routeIs('portal.pengajuan.index') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800' }}">Pengajuan Saya</a>
                        <a href="{{ route('portal.surat-terbit.index') }}" class="px-3 py-1.5 rounded-lg whitespace-nowrap {{ request()->routeIs('portal.surat-terbit.index') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800' }}">Surat Terbit</a>
                        <a href="{{ route('portal.profil') }}" class="px-3 py-1.5 rounded-lg whitespace-nowrap {{ request()->routeIs('portal.profil') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800' }}">Profil Saya</a>
                    </div>
                @endauth
            </header>

            <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6">
                @if (session('status'))
                    <div class="mb-6 rounded-2xl border border-emerald-200/80 bg-emerald-500/10 backdrop-blur-md px-5 py-4 text-sm text-emerald-900 shadow-sm flex items-start gap-3">
                        <div class="h-6 w-6 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">✓</div>
                        <div class="font-medium leading-relaxed">{{ session('status') }}</div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 rounded-2xl border border-rose-200/80 bg-rose-500/10 backdrop-blur-md px-5 py-4 text-sm text-rose-900 shadow-sm flex items-start gap-3">
                        <div class="h-6 w-6 rounded-full bg-rose-600 text-white flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">!</div>
                        <div class="font-medium leading-relaxed">{{ session('error') }}</div>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>

        <!-- Premium Clean Footer -->
        <footer class="bg-white border-t border-slate-200/80 py-8 text-center text-xs text-slate-500 mt-16 shadow-inner">
            <div class="mx-auto max-w-7xl px-4 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 font-semibold text-slate-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 inline-block"></span>
                    <span>SIPADES — Sistem Informasi Pelayanan Desa Karduluk</span>
                </div>
                <div class="text-slate-400">
                    &copy; {{ now()->year }} Pemerintah Desa Karduluk, Kec. Pragaan, Kab. Sumenep. All rights reserved.
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts
</body>
</html>
