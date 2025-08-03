<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::post(
    '/homeowners', [App\Http\Controllers\HomeownersController::class, 'store']
)->name('homeowners.store');
