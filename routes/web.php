<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PeramalanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('auth')->group(function () {
    Route::get('dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::prefix('dashboard')->group(function () {
        // Pemasukan
        Route::resource('pemasukan',PemasukanController::class);
        // Pengeluaran
        Route::resource('pengeluaran',PengeluaranController::class);
        // Peralaman
        Route::get('peramalan',[PeramalanController::class,'index'])->name('peralaman.index');
        // Laporan
        Route::get('laporan',[LaporanController::class,'index'])->name('laporan.index');
        // User
        Route::resource('user',UserController::class);
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
