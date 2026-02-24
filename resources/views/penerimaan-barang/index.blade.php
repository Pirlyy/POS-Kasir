@extends('layouts.app')

@php
// fallback jika controller tidak kirim data
$nomorFaktur = $nomorFaktur ?? \App\Models\PenerimaanBarang::nomorPenerimaan();
@endphp

@section('content')
<div class="row">
    {{-- KIRI : FORM --}}
    <div class="col-lg-8">

        <div class="card shadow-sm mb-3">
            <form action="{{ route('barang-datang.store') }}" method="POST" id="form-penerimaan-barang">
                @csrf

                <div id="data-hidden"></div>

                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">📦 Barang Datang</h5>

                    <button type="submit"
                        class="btn btn-primary"
                        onclick="return items.length > 0 || (alert('Belum ada produk'), false)">
                        💾 Simpan
                    </button>
                </div>

                <div class="card-body">

                    {{-- HEADER --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="small text-muted">Distributor</label>
                            <input type="text" name="distributor" class="form-control" placeholder="Nama distributor">
                        </div>

                        <div class="col-md-6">
                            <label class="small text-muted">Nomor Faktur</label>

                            {{-- AUTO NOMOR FAKTUR --}}
                            <input type="text"
                                   name="nomor_faktur"
                                   class="form-control"
                                   value="{{ $nomorFaktur }}"
                                   readonly>
                        </div>
                    </div>

                    <hr>

                    {{-- INPUT PRODUK --}}
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label class="small text-muted">Produk</label>
                            <select id="select2" class="form-control"></select>
                        </div>

                        <div class="col-md-2">
                            <label class="small text-muted">Stok</label>
                            <input type="number" id="current_stok" class="form-control bg-light" readonly>
                        </div>

                        <div class="col-md-2">
                            <label class="small text-muted">Qty</label>
                            <input type="number" id="qty" class="form-control" placeholder="0">
                        </div>

                        <div class="col-md-2">
                            <label class="small text-muted">Harga Beli</label>
                            <input type="number" id="harga_beli" class="form-control" placeholder="Rp">
                        </div>

                        <div class="col-md-2">
                            <button type="button" id="btn-add" class="btn btn-dark w-100">
                                ➕ Tambah
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <b>📋 Daftar Produk Masuk</b>
            </div>

            <div class="card-body p-0">
                <table class="table table-sm table-hover mb-0" id="table-produk">
                    <thead class="thead-light">
                        <tr>
                            <th>Produk</th>
                            <th width="80">Qty</th>
                            <th width="120">Harga</th>
                            <th width="140">Subtotal</th>
                            <th width="40"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- KANAN : RINGKASAN --}}
    <div class="col-lg-4">
        <div class="card shadow-sm sticky-top" style="top:20px">
            <div class="card-header bg-white">
                <b>🧾 Ringkasan</b>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Item</span>
                    <b id="total-item">0</b>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Total Qty</span>
                    <b id="total-qty">0</b>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <span>Total Nilai</span>
                    <b id="grand-total">Rp 0</b>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
.card {
    border-radius: 8px;
}
</style>
@endpush

@push('script')
<script>
let items = [];

/* =======================
   SELECT2 PRODUK
======================= */
$('#select2').select2({
    theme: 'bootstrap4',
    width: '100%',
    placeholder: 'Cari produk...',
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
    const id = e.params.data.id;

    $.get("{{ route('get-data.cek-stok') }}", { id }, res => {
        $('#current_stok').val(res.stok);
    });
});

/* =======================
   TAMBAH PRODUK
======================= */
$('#btn-add').on('click', function () {
    const id = $('#select2').val();
    const nama = $('#select2').find(':selected').text();
    const qty = parseInt($('#qty').val());
    const stok = parseInt($('#current_stok').val());
    const harga = parseInt($('#harga_beli').val());

    if (!id || !qty || !harga) {
        alert('Lengkapi data');
        return;
    }

    if (qty > stok) {
        alert('Qty melebihi stok');
        return;
    }

    const subtotal = qty * harga;

    items.push({ id, nama, qty, harga, subtotal });
    renderTable();

    $('#select2').val(null).trigger('change');
    $('#qty').val('');
    $('#harga_beli').val('');
    $('#current_stok').val('');
});

/* =======================
   RENDER TABLE
======================= */
function renderTable(){
    let tbody = '';
    let totalQty = 0;
    let totalNilai = 0;

    items.forEach((i, index) => {
        totalQty += i.qty;
        totalNilai += i.subtotal;

        tbody += `
        <tr>
            <td>${i.nama}</td>
            <td>${i.qty}</td>
            <td>Rp ${i.harga.toLocaleString()}</td>
            <td>Rp ${i.subtotal.toLocaleString()}</td>
            <td>
                <button class="btn btn-sm btn-danger" onclick="hapus(${index})">✕</button>
            </td>
        </tr>`;
    });

    $('#table-produk tbody').html(tbody);
    $('#total-item').text(items.length);
    $('#total-qty').text(totalQty);
    $('#grand-total').text('Rp ' + totalNilai.toLocaleString());

    // hidden input
    $('#data-hidden').html('');
    items.forEach((i, x) => {
        $('#data-hidden').append(`
            <input type="hidden" name="produk[${x}][produk_id]" value="${i.id}">
            <input type="hidden" name="produk[${x}][nama_produk]" value="${i.nama}">
            <input type="hidden" name="produk[${x}][qty]" value="${i.qty}">
            <input type="hidden" name="produk[${x}][harga_beli]" value="${i.harga}">
            <input type="hidden" name="produk[${x}][sub_total]" value="${i.subtotal}">
        `);
    });
}

function hapus(i){
    items.splice(i, 1);
    renderTable();
}
</script>
@endpush
