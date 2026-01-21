@extends('layouts.app')

@section('content_title', 'Penerimaan Barang')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Penerimaan Barang</h4>
    </div>

    <div class="card-body">
        <div class="d-flex align-items-end">
    <div class="flex-grow-1" id="produk-wrapper">
        <label>Produk</label>
        <select id="select2" class="form-control w-100"></select>
    </div>

    <div class="ml-2">
        <label>Stok</label>
        <input type="number" id="current_stok" class="form-control" style="width:100px" readonly>
    </div>

    <div class="ml-2">
        <label>Qty</label>
        <input type="number" id="qty" class="form-control" style="width:100px">
    </div>

    <div class="ml-2">
        <button class="btn btn-dark" id="btn-add">Tambahkan</button>
    </div>
</div>

    </div>
</div>

{{-- TABLE TIDAK DIHAPUS --}}
<div class="card mt-3">
    <div class="card-body">
        <table class="table table-sm" id="table-produk">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Qty</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function () {

    $('#select2').select2({
        theme: 'bootstrap',
        width: '100%',
        placeholder: 'Cari Produk...',
        dropdownParent: $('#produk-wrapper'),
        minimumInputLength: 5,
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

    $('#select2').on('select2:select', function (e) {
        const data = e.params.data;
        $('#select2').data('nama', data.text);

        $.get("{{ route('get-data.cek-stok') }}", { id: data.id }, function (res) {
            $('#current_stok').val(res.stok);
        });
    });

    $('#btn-add').on('click', function () {
        const id = $('#select2').val();
        const nama = $('#select2').data('nama');
        const qty = parseInt($('#qty').val());
        const stok = parseInt($('#current_stok').val());

        if (!id) {
            alert('Pilih produk terlebih dahulu');
            return;
        }

        if (!qty || qty <= 0) {
            alert('Qty harus lebih dari 0');
            return;
        }

        if (qty > stok) {
            alert('Stok tidak mencukupi');
            return;
        }

        $('#table-produk tbody').append(`
            <tr>
                <td>${nama}</td>
                <td>${qty}</td>
                <td>
                    <button class="btn btn-danger btn-sm btn-remove">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);

        $('#select2').val(null).trigger('change');
        $('#qty').val('');
        $('#current_stok').val('');
    });

    $('#table-produk').on('click', '.btn-remove', function () {
        $(this).closest('tr').remove();
    });

});
</script>
@endpush

