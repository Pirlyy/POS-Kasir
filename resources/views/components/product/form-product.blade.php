<div>
  <button type="button" class="btn {{ $id ? 'btn-warning' : 'btn-primary' }}" data-toggle="modal"
    data-target="#formProduct{{ $id ?? '' }}">
    @if ($id)
      <i class="fas fa-edit"></i>
    @else
      Product Baru
    @endif
  </button>
  <div class="modal fade" id="formProduct{{ $id ?? '' }}">
    <form action="{{ route('master-data.product.store') }}" method="POST">
      @csrf
      <input type="hidden" name="id" value="{{ $id ?? '' }}">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ $id ? 'Form Edit Product' : 'Form Product Baru' }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group my-1">
              <label for="">Nama Produk</label>
              <input type="text" name="nama_produk" class="form-control"
                value="{{ old('nama_produk', $products->nama_produk ?? '') }}">
            </div>
            <div class="form-group my-1 position-relative" id="kategori_sugestions">
              <label>Kategori Produk</label>

              <input type="text" id="kategori_input" class="form-control" placeholder="Ketik kategori..."
                autocomplete="off">

              <input type="hidden" name="kategori_id" id="kategori_id">

              <div id="kategori_suggestions" class="list-group position-absolute w-100 d-none" style="z-index: 1000;">
              </div>
            </div>

            <div class="form-group my-1">
              <label for="">Harga Jual</label>
              <input type="number" name="harga_jual" id="harga_jual" class="form-control" value="{{ $id ?
              $harga_jual : old('harga_jual') }}">
            </div>
            <div class="form-group my-1">
              <label for="">Harga Beli Pokok</label>
              <input type="number" name="harga_beli_pokok" id="harga_beli_pokok" class="form-control" value="{{ $id ?
              $harga_beli_pokok : old('harga_beli_pokok') }}">
            </div>
            <div class="form-group my-1">
              <label for="">Stok Persediaan</label>
              <input type="number" name="stok" id="stok" class="form-control" value="{{ $id ?
              $stok : old('stok') }}">
            </div>
            <div class="form-group my-1">
              <label for="">Stok Minimal</label>
              <input type="number" name="stok_minimal" id="stok_minimal" class="form-control" value="{{ $id ?
              $stok_minimal : old('stok_minimal') }}">
            </div>
            <div class="form-group my-1 d-flex flex-column">
              <div class="d-flex align-items-center">
                <label for="" class="mr-4">Produk Aktif?</label>
                <input type="checkbox" name="is_active" id="is_active" {{ old('is_active', $id ? $is_active : false) ? 'checked' : ''}}>
              </div>
              <small class="text-secondary -mt-2">Jika Aktif Maka Produk Akan Ditampilkan di halaman kasir</small>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">tambahkan</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </form>
  </div>
  <!-- /.modal -->
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.querySelectorAll('.modal').forEach(modal => {

  const input = modal.querySelector('#kategori_input');
  const hiddenInput = modal.querySelector('#kategori_id');
  const suggestionsBox = modal.querySelector('#kategori_suggestions');

  if (!input || !hiddenInput || !suggestionsBox) return;

  input.addEventListener('input', function () {
    const query = this.value.trim();

    // ⛔ belum mengetik
    if (query.length < 2) {
      suggestionsBox.classList.add('d-none');
      suggestionsBox.innerHTML = '';
      hiddenInput.value = '';
      return;
    }

    fetch(`/master-data/kategori/autocomplete?q=${encodeURIComponent(query)}`)
      .then(res => res.json())
      .then(data => {
        suggestionsBox.innerHTML = '';

        if (!data.length) {
          suggestionsBox.classList.add('d-none');
          return;
        }

        data.forEach(item => {
          const btn = document.createElement('button');
          btn.type = 'button';
          btn.className = 'list-group-item list-group-item-action';
          btn.textContent = item.nama_kategori;

          btn.onclick = () => {
            input.value = item.nama_kategori;
            hiddenInput.value = item.id;
            suggestionsBox.classList.add('d-none');
          };

          suggestionsBox.appendChild(btn);
        });

        suggestionsBox.classList.remove('d-none');
      });
  });

  // klik luar → tutup suggestion
  document.addEventListener('click', e => {
    if (!modal.contains(e.target)) {
      suggestionsBox.classList.add('d-none');
    }
  });

});
</script>

<script>
$('.btn-edit').on('click', function () {

  const modal = $($(this).data('target'));

  modal.find('#kategori_input')
    .val($(this).data('kategori-nama'));

  modal.find('#kategori_id')
    .val($(this).data('kategori-id'));

  modal.find('#kategori_suggestions')
    .addClass('d-none')
    .html('');
});
</script>
