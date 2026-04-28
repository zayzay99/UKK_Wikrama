<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ItemApiController;
use App\Http\Controllers\Api\TransactionApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\ItemController;
use App\Models\Item;

    // Items API
Route::get('/items', [ItemApiController::class, 'index'])->name('api.items.index');
// Route::get('/items/{id}', [ItemApiController::class, 'show'])->name('api.items.show');
Route::post('/items', [ItemApiController::class, 'store'])->name('api.items.store');
Route::put('/items/{id}', [ItemApiController::class, 'update'])->name('api.items.update');
Route::delete('/items/{id}', [ItemApiController::class, 'destroy'])->name('api.items.destroy');