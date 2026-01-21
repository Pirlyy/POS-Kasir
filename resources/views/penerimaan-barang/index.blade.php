@extends('layouts.app')

@section('content_title', 'Penerimaan Barang')

@section('content')
<div class="card">
        <form action="{{route('penerimaan-barang.store')}}" method="post" id="form-penerimaan-barang">
            @csrf
            <div id="data-hidden"></div>
              <div class="d-flex align-items-center justify-content-between p-3 border-buttom">
        <h4 class="h5">Penerimaan Barang</h4>
        <div>
            <button type="submit" class="btn btn-primary">Simpan Penerima</button>
        </div>
    </div>


    <div class="card-body">
        <div class="w-50">
            <div class="form-group my-1">
                <label for="">Distributor</label>
                <input type="text" name="distributor" id="distributor" class="form-control" value="{{old('distributor')}}">
                @error('distributor')
                    <small class="text-danger">{{$message}}</small>
                @enderror
            </div>
            <div class="form-group my-1">
                <label for="">Nomer Faktur</label>
                <input type="text" name="nomor_faktur" id="nomor_faktur" class="form-control" value="{{old('nomor_faktur')}}">
                @error('nomor_faktur')
                <small class="text-danger">{{$message}}</small>

                @enderror
            </div>
        </div>
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
        <label>Harga Beli</label>
        <input type="number" id="harga_beli" class="form-control" style="width:100px">
      </div>

      <div class="ml-2">
        <button type="button" class="btn btn-dark" id="btn-add">Tambahkan</button>
       </div>
     </div>

    </div>
        </form>
</div>

{{-- TABLE TIDAK DIHAPUS --}}
<div class="card mt-3">
    <div class="card-body">
        <table class="table table-sm" id="table-produk">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Qty</th>
                    <th>Harga Beli</th>
                    <th>Sub Total</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@push('css')
<style>
/* tinggi select2 sama kayak input bootstrap */
.select2-container--bootstrap4 .select2-selection--single {
    height: 38px;
}
.select2-container--bootstrap4 .select2-selection__rendered {
    line-height: 38px;
}
.select2-container--bootstrap4 .select2-search--dropdown .select2-search__field {
    height: 38px;
    padding: 6px 12px;
    font-size: 14px;
}
</style>
@endpush


@push('script')
<script>
$(document).ready(function () {

    $('#select2').select2({
    theme: 'bootstrap4',
    width: '100%',
    placeholder: 'Cari Produk...',
    dropdownParent: $('#produk-wrapper'),
    minimumInputLength: 1,
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
        const hargaBeli = $('#harga_beli').val();
        const subTotal = parseInt(qty) * parseInt(hargaBeli);

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
            <tr data-id="${id}">
                <td>${nama}</td>
                <td>${qty}</td>
                <td>${hargaBeli}</td>
                <td>${subTotal}</td>
                <td>
                    <button class="btn btn-danger btn-sm btn-remove">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);

        $('#select2').val(null).trigger('change');
        $('#qty').val('');
        $('#harga_beli').val('');
        $('#current_stok').val('');
    });

    $('#table-produk').on('click', '.btn-remove', function () {
        $(this).closest('tr').remove();
    });

    $("#form-penerimaan-barang").on("submit", function () {
    $("#data-hidden").html("");

    $("#table-produk tbody tr").each(function(index, row){

        const produkId   = $(row).data("id");
        const namaProduk = $(row).find("td:eq(0)").text();
        const qty        = $(row).find("td:eq(1)").text();
        const hargaBeli  = $(row).find("td:eq(2)").text();
        const subTotal   = $(row).find("td:eq(3)").text();

        $("#data-hidden").append(`
            <input type="hidden" name="produk[${index}][produk_id]" value="${produkId}">
            <input type="hidden" name="produk[${index}][nama_produk]" value="${namaProduk}">
            <input type="hidden" name="produk[${index}][qty]" value="${qty}">
            <input type="hidden" name="produk[${index}][harga_beli]" value="${hargaBeli}">
            <input type="hidden" name="produk[${index}][sub_total]" value="${subTotal}">
        `);
    });
});


});
</script>
@endpush

