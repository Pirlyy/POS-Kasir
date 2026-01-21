@extends('layouts.app')

@section('content_title', 'Pengeluaran Barang / Transaksi')

@section('content')
<x-alert :errors="$errors" />
<form method="POST" action="{{ route('pengeluaran-barang.store') }}" id="form-transaksi">
@csrf

<div class="row">
    <div class="col-md-8">

        {{-- INPUT PRODUK --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-md-6" id="produk-wrapper">
                        <label>Produk</label>
                        <select id="produk" class="form-control"></select>
                    </div>

                    <div class="col-md-2">
                        <label>Stok</label>
                        <input type="text" id="stok" class="form-control" readonly>
                    </div>

                    <div class="col-md-2">
                        <label>Harga</label>
                        <input type="text" id="harga" class="form-control" readonly>
                    </div>

                    <div class="col-md-2">
                        <label>Qty</label>
                        <input type="number" id="qty" class="form-control">
                    </div>
                </div>

                <button type="button" id="btn-tambah" class="btn btn-dark mt-3">
                    Tambahkan
                </button>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card">
            <div class="card-body">
                <table class="table table-sm" id="table-produk">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Qty</th>
                            <th>Sub Total</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- SIDEBAR TOTAL --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <label>Total</label>
                <input type="text" id="total" class="form-control mb-2" readonly>

                <label>Jumlah Bayar</label>
                <input type="number" name="bayar" id="bayar" class="form-control mb-2">

                <label>Kembalian</label>
                <input type="text" id="kembalian" class="form-control mb-3" readonly>

                <button class="btn btn-primary w-100">
                    Simpan Transaksi
                </button>
            </div>
        </div>
    </div>
</div>

<div id="hidden-input"></div>
</form>
@endsection

@push('script')
<script>
$(function () {

    let total = 0;

    // SELECT2
    $('#produk').select2({
        placeholder: 'Cari Produk...',
        dropdownParent: $('#produk-wrapper'),
        ajax: {
            url: "{{ route('get-data.produk') }}",
            dataType: 'json',
            delay: 250,
            data: params => ({ search: params.term }),
            processResults: data => ({
                results: data.map(item => ({
                    id: item.id,
                    text: item.nama_produk
                }))
            })
        }
    });

    // AUTO HARGA & STOK
    $('#produk').on('select2:select', function (e) {
        let produk = e.params.data;

        $('#produk').data('id', produk.id);
        $('#produk').data('nama', produk.text);

        $.get("{{ route('get-data.cek-stok') }}", { id: produk.id }, function (res) {
            $('#stok').val(res.stok);
            $('#harga').val(res.harga_jual);
        });
    });

    // TAMBAH PRODUK
    $('#btn-tambah').click(function () {

        let id    = $('#produk').data('id');
        let nama  = $('#produk').data('nama');
        let harga = parseInt($('#harga').val());
        let qty   = parseInt($('#qty').val());
        let stok  = parseInt($('#stok').val());

        if (!id || !qty || qty > stok) {
            alert('Data tidak valid');
            return;
        }

        let sub = harga * qty;
        total += sub;

        $('#table-produk tbody').append(`
            <tr data-id="${id}" data-sub="${sub}">
                <td>${nama}</td>
                <td>${qty}</td>
                <td>${sub}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm btn-hapus">🗑</button>
                </td>
            </tr>
        `);

        $('#total').val(total);

        $('#produk').val(null).trigger('change');
        $('#qty').val('');
        $('#harga').val('');
        $('#stok').val('');
    });

    // HAPUS
    $('#table-produk').on('click', '.btn-hapus', function () {
        let tr = $(this).closest('tr');
        total -= parseInt(tr.data('sub'));
        $('#total').val(total);
        tr.remove();
    });

    // HITUNG KEMBALIAN
    $('#bayar').on('keyup', function () {
        let bayar = parseInt($(this).val()) || 0;
        $('#kembalian').val(bayar - total);
    });

    // SUBMIT
    $('#form-transaksi').submit(function () {
        $('#hidden-input').html('');

        $('#table-produk tbody tr').each(function (i, tr) {
            $('#hidden-input').append(`
                <input type="hidden" name="produk[${i}][produk_id]" value="${$(tr).data('id')}">
                <input type="hidden" name="produk[${i}][qty]" value="${$(tr).find('td:eq(1)').text()}">
                <input type="hidden" name="produk[${i}][sub_total]" value="${$(tr).data('sub')}">
                <input type="hidden" name="produk[${i}][nama_produk]" value="${$(tr).find('td:eq(0)').text()}">
            `);
        });
    });

});
</script>
@endpush
