<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('pages.auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['api.auth', 'api.admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.admin.dashboard');
    })->name('admin.dashboard');
});
