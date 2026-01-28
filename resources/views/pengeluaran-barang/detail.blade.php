@extends('layouts.app')

@section('content_title', 'Laporan Pengeluaran Barang')

@section('content')
<div class="card">

    <div class="card-header">
        <h4 class="card-title">
            Laporan Pengeluaran Barang (Transaksi)
            #{{ $pengeluaran->nomor_pengeluaran }}

            <a href="{{ route('laporan.pengeluaran-barang.laporan') }}"
               class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </h4>
    </div>

    <div class="card-body">

        <div class="mb-4">
            <p><strong>Tanggal :</strong>
                {{ \Carbon\Carbon::parse($pengeluaran->created_at)->locale('id')->translatedFormat('l, d F Y') }}
            </p>
            <p><strong>Nama Petugas :</strong>
                {{ ucwords($pengeluaran->nama_petugas) }}
            </p>
            <p><strong>Jumlah Bayar :</strong>
                Rp. {{ number_format($pengeluaran->bayar) }}
            </p>
            <p><strong>Kembalian :</strong>
                Rp. {{ number_format($pengeluaran->kembalian) }}
            </p>
            <p><strong>Total Harga :</strong>
                Rp. {{ number_format($pengeluaran->total_harga) }}
            </p>
        </div>

        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>
@foreach ($pengeluaran->items as $i => $item)
<tr>
    <td>{{ $i + 1 }}</td>
    <td>{{ $item->product->nama_produk ?? '-' }}</td>
    <td>{{ $item->jumlah }} pcs</td>
    <td>Rp. {{ number_format($item->harga_jual) }}</td>
    <td>Rp. {{ number_format($item->sub_total) }}</td>
</tr>
@endforeach
<tr>
    <th colspan="4" class="text-end">Total</th>
    <th>Rp. {{ number_format($pengeluaran->total_harga) }}</th>
</tr>
</tbody>
        </table>

    </div>
</div>
@endsection
