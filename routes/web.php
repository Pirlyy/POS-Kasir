<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PenerimaanBarangController;
use App\Http\Controllers\PengeluaranBarangController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KasirController;

Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

Route::post('/login', [LoginController::class, 'handleLogin'])
    ->name('login')
    ->middleware('guest');

Route::middleware('auth')->group(function () {

    // =====================
    // LOGOUT & DASHBOARD
    // =====================
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =====================
    // AJAX / GET DATA
    // =====================
    Route::get('/cek-harga-produk', [PengeluaranBarangController::class, 'cekharga'])
        ->name('get-data.cek-stok');

    Route::prefix('get-data')->as('get-data.')->group(function () {
        Route::get('/produk', [ProductController::class, 'getData'])->name('produk');
        Route::get('/cek-stok-produk', [ProductController::class, 'cekStok'])->name('cek-stok');
        Route::get('/cek-harga-pack', [ProductController::class, 'cekHarga'])->name('cek-harga');
    });

    // =====================
    // USERS
    // =====================
    Route::prefix('users')
        ->as('users.')
        ->controller(UsersController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::delete('/destroy/{id}', 'destroy')->name('destroy');
            Route::post('/gantipassword', 'gantipassword')->name('ganti-password');
            Route::post('/reset-password', 'resetpassword')->name('reset-password');
        });

    /*
    |--------------------------------------------------------------------------
    | KASIR (POS MODE) - ONLY ROLE KASIR
    |--------------------------------------------------------------------------
    */
    Route::middleware(['kasir'])->group(function () {

        // halaman kasir
        Route::get('/kasir', [KasirController::class, 'index'])
            ->name('kasir.index');

        // ✅ MIDTRANS SNAP TOKEN (QRIS SANDBOX)
        Route::post('/kasir/midtrans-token', [KasirController::class, 'midtransToken'])
            ->name('kasir.midtrans.token');
    });

    // =====================
    // MASTER DATA
    // =====================
    Route::prefix('master-data')->as('master-data.')->group(function () {

        Route::prefix('kategori')
            ->as('kategori.')
            ->controller(KategoriController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::delete('/destroy/{id}', 'destroy')->name('destroy');
            });

        Route::prefix('product')
            ->as('product.')
            ->controller(ProductController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::delete('/destroy/{id}', 'destroy')->name('destroy');

                Route::get('/autocomplete', function (Illuminate\Http\Request $request) {
                    return \App\Models\Kategori::where(
                        'nama_kategori',
                        'like',
                        '%' . $request->q . '%'
                    )->limit(5)->get();
                });
            });
    });

    // =====================
    // TRANSAKSI
    // =====================
    Route::prefix('penerimaan-barang')
        ->as('penerimaan-barang.')
        ->controller(PenerimaanBarangController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
        });

    Route::prefix('pengeluaran-barang')
        ->as('pengeluaran-barang.')
        ->controller(PengeluaranBarangController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/print', 'print')->name('print');
        });

    // =====================
    // LAPORAN
    // =====================
    Route::prefix('laporan')->as('laporan.')->group(function () {

        Route::prefix('penerimaan-barang')
            ->as('penerimaan-barang.')
            ->controller(PenerimaanBarangController::class)
            ->group(function () {
                Route::get('/laporan', 'laporan')->name('laporan');
                Route::get('/laporan/{nomor_penerimaan}/detail', 'detailLaporan')
                    ->name('detail-laporan');
            });

        Route::prefix('pengeluaran-barang')
            ->as('pengeluaran-barang.')
            ->controller(PengeluaranBarangController::class)
            ->group(function () {
                Route::get('/laporan', 'laporan')->name('laporan');
            });
    });
});
