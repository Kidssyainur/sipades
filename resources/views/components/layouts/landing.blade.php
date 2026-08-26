@props(['title' => 'Desa Karduluk — Sentra Ukir Madura'])

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <script>document.documentElement.classList.add('js');</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | Pemerintah Desa Karduluk</title>
    <meta name="description" content="Desa Karduluk, Kecamatan Pragaan, Kabupaten Sumenep — sentra kerajinan ukir kayu Madura dengan wisata Batu Sulung, viaduk kolonial, dan layanan surat-menyurat online.">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title }} | Pemerintah Desa Karduluk">
    <meta property="og:description" content="Sentra Ukir Madura — profil, potensi, wisata, dan layanan digital Desa Karduluk, Kec. Pragaan, Kab. Sumenep.">

    <!-- Favicon & logo desa -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/webp" sizes="32x32" href="{{ asset('favicon.webp') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <meta name="theme-color" content="#0f172a">

    <!-- Google Fonts: Plus Jakarta Sans + Fraunces (display serif) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,400;1,9..144,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-950 font-sans text-slate-300 antialiased selection:bg-amber-400/30 selection:text-amber-950">

    @include('landing.partials.navbar')

    <main>
        {{ $slot }}
    </main>

    @include('landing.partials.footer')

    @stack('scripts')
</body>
</html>
