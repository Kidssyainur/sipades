<?php

use App\Http\Controllers\VerifikasiSuratController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->isWarga()
            ? redirect()->route('portal.dashboard')
            : redirect('/admin');
    }

    return redirect()->route('portal.login');
});

// Route publik verifikasi keabsahan TTE dokumen resmi
Route::get('/verifikasi-surat/{token}', VerifikasiSuratController::class)->name('surat.verifikasi');
