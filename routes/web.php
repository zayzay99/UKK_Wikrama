<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

// Auth Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    
    // Akses Admin: Input Data, Transaksi, Report
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('items', ItemController::class); // Input Alat/Buku
        Route::resource('users', UserController::class)->except(['show']); // Input Akun Petugas/Siswa
        Route::get('/reports', [TransactionController::class, 'report'])->name('reports.index');
        Route::post('/reports/penalty-settings', [TransactionController::class, 'updatePenaltySettings'])->name('reports.penalty-settings');
    });

    // Akses Petugas: Transaksi
    Route::middleware(['role:petugas,admin'])->group(function () {
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::post('/transactions/borrow', [TransactionController::class, 'borrow'])->name('transactions.borrow'); // Form peminjaman
        Route::post('/transactions/requests/{borrowRequest}/process', [TransactionController::class, 'processBorrowRequest'])->name('transactions.requests.process');
        Route::post('/transactions/return/{id}', [TransactionController::class, 'returnItem'])->name('transactions.return'); // Form pengembalian
    });

    // Akses Siswa: Riwayat
    Route::middleware(['role:siswa'])->group(function () {
        Route::get('/my-history', [TransactionController::class, 'siswaHistory'])->name('siswa.history');
        Route::post('/my-history/request', [TransactionController::class, 'submitBorrowRequest'])->name('siswa.requests.store');
    });
});
