<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    // Auth Siswa & Petugas
    Route::get('/login', [AuthController::class, 'showStudentAuth'])->name('login');
    Route::post('/login', [AuthController::class, 'studentLogin'])->name('student.login');
    Route::post('/register', [AuthController::class, 'studentRegister'])->name('student.register');
    Route::post('/petugas/login', [AuthController::class, 'petugasLogin'])->name('petugas.login.submit');

    // Auth Admin
    Route::get('/admin/login', [AuthController::class, 'showAdminAuth'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');
    Route::post('/admin/register', [AuthController::class, 'adminRegister'])->name('admin.register');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    
    // Akses Admin: Input Data, Transaksi, Report
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/items/{item}/print', [ItemController::class, 'print'])->name('items.print');
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
