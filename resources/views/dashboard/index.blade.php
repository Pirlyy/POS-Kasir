@extends('layouts.app')

@section('content_title', 'Dashboard')

@section('content')

{{-- ROW CARD DASHBOARD --}}
<div class="row">

    <x-dashboard-card
        type="bg-info"
        icon="fas fa-users"
        label="Total Users"
        value="{{ $totalUsers }}"
    />

    <x-dashboard-card
        type="bg-warning"
        icon="fas fa-box"
        label="Total Produk"
        value="{{ $totalProduk }}"
    />

    <x-dashboard-card
        type="bg-success"
        icon="fas fa-shopping-bag"
        label="Total Order"
        value="{{ $totalOrder }}"
    />

    <x-dashboard-card
        type="bg-teal"
        icon="fas fa-dollar-sign"
        label="Total Pendapatan"
        value="{{ $totalPendapatan }}"
    />

</div>

<div class="row">
    <!-- ================= KIRI : TRANSAKSI TERAKHIR ================= -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Transaksi Terakhir</h4>
            </div>

            <div class="card-body p-0">
                <table class="table table-sm table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal Transaksi</th>
                            <th>Nomor Transaksi</th>
                            <th>Jumlah Item</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestOrders as $item)
                            <tr>
                                <td>{{ $item->tanggal_transaksi }}</td>
                                <td>{{ $item->nomor_pengeluaran }}</td>
                                <td>
                                    {{ $item->items->count() }}
                                    <small>Item</small>
                                </td>
                                <td>
                                    Rp. {{ number_format($item->total_harga) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Belum ada transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer text-muted">
                Menampilkan 5 Data Transaksi Terakhir
            </div>
        </div>
    </div>

    <!-- ================= KANAN : PRODUK TERLARIS ================= -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Produk Terlaris</h4>
            </div>

            <div class="card-body p-0">
                <table class="table table-sm table-bordered mb-0">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>Nama Produk</th>
                            <th width="120">Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($produkTerlaris as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->nama_produk }}</td>
                                <td>{{ $item->total_terjual }} Item</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    Belum ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



@endsection
 