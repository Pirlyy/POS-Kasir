@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-4">Penerimaan Barang</h4>

    <form action="{{ route('penerimaan-barang.store') }}" method="POST">
        @csrf

        <div class="card">
            <div class="card-body">

                {{-- NOMOR FAKTUR AUTO --}}
                <div class="form-group mb-3">
                    <label>Nomor Faktur</label>
                    <input
                        type="text"
                        class="form-control"
                        value="{{ $nomorFaktur }}"
                        readonly
                    >

                    {{-- dikirim ke controller --}}
                    <input
                        type="hidden"
                        name="nomor_faktur"
                        value="{{ $nomorFaktur }}"
                    >
                </div>

                {{-- DISTRIBUTOR --}}
                <div class="form-group mb-3">
                    <label>Distributor</label>
                    <input
                        type="text"
                        name="distributor"
                        class="form-control"
                        required
                    >
                </div>

                {{-- PRODUK --}}
                <div id="produk-wrapper">
                    <div class="row mb-2 produk-item">
                        <div class="col-md-4">
                            <input type="number" name="produk[0][produk_id]" class="form-control" placeholder="ID Produk">
                        </div>

                        <div class="col-md-3">
                            <input type="number" name="produk[0][qty]" class="form-control" placeholder="Qty">
                        </div>

                        <div class="col-md-3">
                            <input type="number" name="produk[0][harga_beli]" class="form-control" placeholder="Harga Beli">
                        </div>

                        <div class="col-md-2">
                            <input type="number" name="produk[0][sub_total]" class="form-control" placeholder="Subtotal">
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-sm btn-secondary" id="addProduk">
                    + Tambah Produk
                </button>

            </div>

            <div class="card-footer">
                <button class="btn btn-primary">Simpan</button>
            </div>
        </div>

    </form>
</div>

<script>
let index = 1;

document.getElementById('addProduk').onclick = function() {
    let wrapper = document.getElementById('produk-wrapper');

    let html = `
    <div class="row mb-2 produk-item">
        <div class="col-md-4">
            <input type="number" name="produk[${index}][produk_id]" class="form-control" placeholder="ID Produk">
        </div>

        <div class="col-md-3">
            <input type="number" name="produk[${index}][qty]" class="form-control" placeholder="Qty">
        </div>

        <div class="col-md-3">
            <input type="number" name="produk[${index}][harga_beli]" class="form-control" placeholder="Harga Beli">
        </div>

        <div class="col-md-2">
            <input type="number" name="produk[${index}][sub_total]" class="form-control" placeholder="Subtotal">
        </div>
    </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);
    index++;
}
</script>

@endsection
