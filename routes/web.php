<?php

use App\Http\Controllers\BeritaController;
use App\Http\Controllers\VerifikasiSuratController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->isWarga()
            ? redirect()->route('portal.dashboard')
            : redirect('/admin');
    }

    return view('landing.index');
})->name('home');

// Route publik verifikasi keabsahan TTE dokumen resmi
Route::get('/verifikasi-surat/{token}', VerifikasiSuratController::class)->name('surat.verifikasi');

// Route publik detail berita dari manajemen berita (landing page)
Route::get('/berita/{berita:slug}', [BeritaController::class, 'show'])->name('berita.show');
