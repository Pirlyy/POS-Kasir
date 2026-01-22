@extends('layouts.app')

@section('content_title', 'Laporan Pengeluaran Barang')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Laporan Pengeluaran Barang</h4>
    </div>

    <div class="card-body">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Petugas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $i => $item)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $item->nomor_pengeluaran }}</td>
                    <td>{{ $item->created_at->format('d-m-Y') }}</td>
                    <td>Rp {{ number_format($item->total_harga) }}</td>
                    <td>{{ $item->nama_petugas }}</td>
                    <td>
                        <a href="{{ route('laporan.pengeluaran-barang.detail-laporan', $item->nomor_pengeluaran) }}"
                           class="btn btn-info btn-sm">
                            Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
