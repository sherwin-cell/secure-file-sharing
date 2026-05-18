<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register']);

Route::middleware('auth')->group(function () {

    Route::get('/dashboard',             [FileController::class, 'dashboard'])->name('dashboard');

    Route::post('/files/upload',         [FileController::class, 'upload'])->name('files.upload');
    Route::get('/files/{file}/download', [FileController::class, 'download'])->name('files.download');
    Route::delete('/files/{file}',       [FileController::class, 'delete'])->name('files.delete');

    Route::post('/logout',               [AuthController::class, 'logout'])->name('logout');

});