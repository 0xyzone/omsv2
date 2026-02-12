<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrintController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::passkeys();
Route::get('/print/{id}', [PrintController::class, 'print'])->name('print');