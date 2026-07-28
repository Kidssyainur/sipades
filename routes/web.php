<?php

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
