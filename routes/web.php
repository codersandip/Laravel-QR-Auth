<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/qr-login', [App\Http\Controllers\HomeController::class, 'qrLogin'])->name('qr.login');
Route::post('/qr-login', [App\Http\Controllers\HomeController::class, 'qrLoginCheck']);
Route::get('/qr-login-show', [App\Http\Controllers\HomeController::class, 'qrLoiginShow'])->name('qr.login.show')->middleware('auth');
Route::post('/qr-login-show', [App\Http\Controllers\HomeController::class, 'qrLoiginVerify'])->name('qr.login.verify')->middleware('auth');
