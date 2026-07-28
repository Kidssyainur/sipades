<?php

use App\Http\Controllers\UnduhSuratController;
use App\Livewire\Portal\Dashboard;
use App\Livewire\Portal\OtpVerifikasi;
use App\Livewire\Portal\PengajuanSuratForm;
use App\Livewire\Portal\PortalLogin;
use App\Livewire\Portal\RegistrasiForm;
use App\Livewire\Portal\TrackingStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Portal Warga (di luar panel Filament) — PRD §13
|--------------------------------------------------------------------------
|
| Rute publik & area warga berbasis Blade + Livewire. Grup /portal/*
| dilindungi middleware auth + role:warga (kecuali registrasi & login).
|
*/

// --- Rute publik: registrasi & login (tamu) ---
Route::middleware('guest')->group(function () {
    Route::get('/registrasi', RegistrasiForm::class)->name('registrasi');
    Route::get('/verifikasi-otp', OtpVerifikasi::class)->name('verifikasi-otp');
    Route::get('/portal/login', PortalLogin::class)->name('portal.login');
});

// --- Logout ---
Route::post('/portal/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('portal.login');
})->name('portal.logout')->middleware('auth');

// --- Area warga terproteksi ---
Route::middleware(['auth', 'role:warga'])->group(function () {
    Route::get('/portal/dashboard', Dashboard::class)->name('portal.dashboard');
    Route::get('/portal/pengajuan/buat', PengajuanSuratForm::class)->name('portal.pengajuan.buat');
    Route::get('/portal/pengajuan/{pengajuan}/revisi', PengajuanSuratForm::class)->name('portal.pengajuan.revisi');
    Route::get('/portal/pengajuan/{pengajuan}/status', TrackingStatus::class)->name('portal.pengajuan.status');
});

// Unduh file PDF surat via signed URL (kedaluwarsa 7 hari) — PRD §11.5 poin 6.
Route::get('/portal/surat/{surat}/unduh', UnduhSuratController::class)
    ->name('surat.unduh')
    ->middleware('signed');
