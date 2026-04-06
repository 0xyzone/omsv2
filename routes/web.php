<?php

use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\PrintController;
use Illuminate\Support\Facades\Route;

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

Route::post('/custom-logout', [LogoutController::class, 'logout'])->name('custom.logout');
Route::get('/demo', function () {
    return view('errors.415');
})->name('demo');