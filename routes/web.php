<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrintController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::passkeys();
Route::get('/print/{id}', [PrintController::class, 'print'])->name('print');
Route::get('/login', [App\Http\Controllers\LoginController::class, 'index'])->name('login');
Route::post('/login', [App\Http\Controllers\LoginController::class, 'authenticate'])->name('login.authenticate');

Route::get('/suspended', function () {
    return view('errors.suspended');
})->name('suspended.notice');