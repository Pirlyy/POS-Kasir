<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PenerimaanBarangController;


Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'handleLogin'])->name('login')->middleware('guest');

Route::middleware('auth')->group(function() {
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard');


    Route::prefix('get-data')->as('get-data.')->group(function (){
        Route::get('/produk',[ProductController::class, 'getData'])->name('produk');
             Route::get('/cek-stok-produk',[ProductController::class, 'cekStok'])->name('cek-stok');
    });
    //master-data.kategori.index
    //master-data/kategori/index
    Route::prefix('users')->as('users.')->controller(UsersController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::delete('/destroy/{id}', 'destroy')->name('destroy');
        Route::post('/gantipassword', 'gantipassword')->name('ganti-password');
        Route::post('/reset-password', 'resetpassword')->name('reset-password');
    });

    Route::prefix('master-data')->as('master-data.')->group(function(){
        Route::prefix('kategori')->as('kategori.')->controller(KategoriController::class)->group(function(){
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::delete('/destroy/{id}', 'destroy')->name('destroy');
        });
        Route::prefix('product')->as('product.')->controller(ProductController::class)->group(function() {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::delete('/destroy/{id}', 'destroy')->name('destroy');
        });
    });

    Route::prefix('penerimaan-barang')->as('penerimaan-barang.')->controller(PenerimaanBarangController::class)->group(function (){
         Route::get('/', 'index')->name('index');
         Route::post('/', 'store')->name('store');

    });
});
