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

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

Route::post('/login', [LoginController::class, 'handleLogin'])
    ->name('login')
    ->middleware('guest');


/*
|--------------------------------------------------------------------------
| AUTHENTICATED AREA
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | ADMIN AREA
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // USERS
        Route::prefix('users')->as('users.')->controller(UsersController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::delete('/destroy/{id}', 'destroy')->name('destroy');
            Route::post('/gantipassword', 'gantipassword')->name('ganti-password');
            Route::post('/reset-password', 'resetpassword')->name('reset-password');
        });

        /*
        |--------------------------------------------------------------------------
        | MASTER DATA
        |--------------------------------------------------------------------------
        */
        Route::prefix('master-data')->as('master-data.')->group(function () {

            Route::prefix('kategori')->as('kategori.')
                ->controller(KategoriController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'store')->name('store');
                    Route::delete('/destroy/{id}', 'destroy')->name('destroy');
                });

            Route::prefix('product')->as('product.')
                ->controller(ProductController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'store')->name('store');
                    Route::delete('/destroy/{id}', 'destroy')->name('destroy');
                });
        });

        /*
        |--------------------------------------------------------------------------
        | TRANSAKSI ADMIN
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | LAPORAN ADMIN
        |--------------------------------------------------------------------------
        */
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
                    Route::get('/laporan/{nomor_pengeluaran}/detail', 'detailLaporan')
                        ->name('detail-laporan');
                });
        });
    });


    /*
    |--------------------------------------------------------------------------
    | KASIR AREA (POS SYSTEM)
    |--------------------------------------------------------------------------
    */
    Route::middleware('kasir')->group(function () {

        // halaman POS
        Route::get('/kasir', [KasirController::class, 'index'])
            ->name('kasir.index');

        // ⭐ SIMPAN TRANSAKSI POS (WAJIB UNTUK STRUK)
        Route::post('/kasir/simpan', [KasirController::class, 'simpanTransaksi'])
            ->name('kasir.simpan');

        // MIDTRANS QRIS
        Route::post('/kasir/midtrans-token', [KasirController::class, 'midtransToken'])
            ->name('kasir.midtrans.token');

        // PRINT STRUK
        Route::get('/kasir/pengeluaran/{id}/print',
            [PengeluaranBarangController::class, 'print']
        )->name('kasir.pengeluaran.print');

        // barang datang mode kasir
        Route::get('/barang-datang', function () {
            return view('penerimaan-barang.standalone');
        })->name('barang-datang');

        Route::post('/barang-datang', [PenerimaanBarangController::class, 'store'])
            ->name('barang-datang.store');
    });


    /*
    |--------------------------------------------------------------------------
    | AJAX
    |--------------------------------------------------------------------------
    */
    Route::prefix('get-data')->as('get-data.')->group(function () {
        Route::get('/produk', [ProductController::class, 'getData'])->name('produk');
        Route::get('/cek-stok-produk', [ProductController::class, 'cekStok'])->name('cek-stok');
        Route::get('/cek-harga-pack', [ProductController::class, 'cekHarga'])->name('cek-harga');
    });

});
