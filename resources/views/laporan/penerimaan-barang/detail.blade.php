@extends('layouts.app')
@section('content_title', 'Laporan Penerimaan Barang')
@section('content')

<div class="card">
    <div class="card-header border-bottom">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0 font-weight-bold">PT NUSA INDO APP</h4>
            <small class="text-muted">Laporan Penerimaan Barang</small>
        </div>

        <div class="d-flex align-items-center">
            <small class="text-muted mr-3">{{ $data->tanggal_penerimaan }}</small>

            <a href="{{ route('laporan.penerimaan-barang.laporan') }}"
               class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>
</div>


    <div class="card-body">

        {{-- INFO --}}
        <table class="table table-borderless table-sm mb-4">
            <tr>
                <td width="15%" class="font-weight-bold">Distributor</td>
                <td width="35%">: {{ $data->distributor }}</td>

                <td width="20%" class="font-weight-bold">Petugas</td>
                <td width="30%">: {{ $data->petugas_penerima }}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Nomor Faktur</td>
                <td>: {{ $data->nomor_faktur }}</td>
                <td></td>
                <td></td>
            </tr>
        </table>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
                <thead class="bg-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Produk</th>
                        <th width="10%" class="text-right">Qty</th>
                        <th width="15%" class="text-right">Harga Beli</th>
                        <th width="15%" class="text-right">Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama_produk }}</td>
                        <td class="text-right">{{ number_format($item->qty) }} PCS</td>
                        <td class="text-right">Rp {{ number_format($item->harga_beli) }}</td>
                        <td class="text-right">Rp {{ number_format($item->sub_total) }}</td>
                    </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Total Pembelian</th>
                        <th class="text-right">Rp {{ number_format($data->total) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
</div>

@endsection
