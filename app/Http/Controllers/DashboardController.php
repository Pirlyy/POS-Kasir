<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\PengeluaranBarang;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // =====================
        // INFO ATAS (CARD)
        // =====================
        $totalUsers  = User::count();
        $totalProduk = Product::count();

        $totalOrder = PengeluaranBarang::whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->count();

        $totalPendapatan = PengeluaranBarang::whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->sum('total_harga');

        $totalPendapatan = 'Rp. ' . number_format($totalPendapatan);

        // =====================
        // TRANSAKSI TERAKHIR
        // =====================
        $latestOrders = PengeluaranBarang::with('items')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                $item->tanggal_transaksi = Carbon::parse($item->created_at)
                    ->translatedFormat('l, d-m-Y');
                return $item;
            });

        // =====================
        // PRODUK TERLARIS (JOIN FIX)
        // =====================
        $produkTerlaris = DB::table('item_pengeluaran_barangs as item')
            ->join('products as p', 'p.id', '=', 'item.product_id')
            ->select(
                'p.nama_produk',
                DB::raw('SUM(item.jumlah) as total_terjual')
            )
            ->whereMonth('item.created_at', $bulanIni)
            ->whereYear('item.created_at', $tahunIni)
            ->groupBy('p.nama_produk')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalUsers',
            'totalProduk',
            'totalOrder',
            'totalPendapatan',
            'latestOrders',
            'produkTerlaris'
        ));
    }
}
