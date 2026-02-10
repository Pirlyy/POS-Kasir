<div>
  <button type="button"
          class="btn {{ $id ? 'btn-warning' : 'btn-primary' }}"
          data-toggle="modal"
          data-target="#formProduct{{ $id ?? '' }}">
    @if ($id)
      <i class="fas fa-edit"></i>
    @else
      Product Baru
    @endif
  </button>

  <div class="modal fade" id="formProduct{{ $id ?? '' }}">
    <form action="{{ route('master-data.product.store') }}"
          method="POST"
          enctype="multipart/form-data">
      @csrf

      <input type="hidden" name="id" value="{{ $id ?? '' }}">

      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <h4 class="modal-title">
              {{ $id ? 'Form Edit Product' : 'Form Product Baru' }}
            </h4>
            <button type="button" class="close" data-dismiss="modal">
              <span>&times;</span>
            </button>
          </div>

          <div class="modal-body">

            {{-- Nama Produk --}}
            <div class="form-group my-1">
              <label>Nama Produk</label>
              <input type="text"
                     name="nama_produk"
                     class="form-control"
                     value="{{ old('nama_produk', $nama_produk ?? '') }}">
            </div>

            {{-- Image --}}
            <div class="form-group my-1">
              <label>Gambar Produk</label>
              <input type="file" name="image" class="form-control">
            </div>

            @if(!empty($image))
              <div class="mb-2">
                <img src="{{ asset('storage/' . $image) }}"
                     width="120"
                     class="img-thumbnail">
              </div>
            @endif

            {{-- Kategori Autocomplete --}}
            <div class="form-group my-1 position-relative">
              <label>Kategori Produk</label>

              <input type="text"
                     class="form-control kategori-input"
                     autocomplete="off"
                     value="{{ old('kategori_nama', $kategori_id ? optional($kategori->find($kategori_id))->nama_kategori : '') }}">

              <input type="hidden"
                     name="kategori_id"
                     class="kategori-id"
                     value="{{ old('kategori_id', $kategori_id ?? '') }}">

              <div class="list-group position-absolute w-100 d-none kategori-suggestions"></div>
            </div>

            {{-- Harga --}}
            <div class="form-group my-1">
              <label>Harga Jual</label>
              <input type="number"
                     name="harga_jual"
                     class="form-control"
                     value="{{ old('harga_jual', $harga_jual ?? '') }}">
            </div>

            <div class="form-group my-1">
              <label>Harga Beli Pokok</label>
              <input type="number"
                     name="harga_beli_pokok"
                     class="form-control"
                     value="{{ old('harga_beli_pokok', $harga_beli_pokok ?? '') }}">
            </div>

            {{-- Stok --}}
            <div class="form-group my-1">
              <label>Stok Persediaan</label>
              <input type="number"
                     name="stok"
                     class="form-control"
                     value="{{ old('stok', $stok ?? '') }}">
            </div>

            <div class="form-group my-1">
              <label>Stok Minimal</label>
              <input type="number"
                     name="stok_minimal"
                     class="form-control"
                     value="{{ old('stok_minimal', $stok_minimal ?? '') }}">
            </div>

            {{-- Active --}}
            <div class="form-group my-1 d-flex flex-column">
              <div class="d-flex align-items-center">
                <label class="mr-3">Produk Aktif?</label>
                <input type="checkbox"
                       name="is_active"
                       {{ old('is_active', $is_active ?? false) ? 'checked' : '' }}>
              </div>
              <small class="text-secondary">
                Jika aktif, produk akan tampil di halaman kasir
              </small>
            </div>

          </div>

          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
              Close
            </button>
            <button type="submit" class="btn btn-primary">
              {{ $id ? 'Update' : 'Tambahkan' }}
            </button>
          </div>

        </div>
      </div>
    </form>
  </div>
</div>

{{-- ================= AUTOCOMPLETE SCRIPT ================= --}}
<script>
document.addEventListener('input', function (e) {

  if (!e.target.classList.contains('kategori-input')) return;

  const input = e.target;
  const wrapper = input.closest('.form-group');
  const hiddenInput = wrapper.querySelector('.kategori-id');
  const suggestionsBox = wrapper.querySelector('.kategori-suggestions');

  const query = input.value.trim();

  if (query.length < 2) {
    suggestionsBox.classList.add('d-none');
    suggestionsBox.innerHTML = '';
    hiddenInput.value = '';
    return;
  }

  fetch(`/master-data/product/autocomplete?q=${encodeURIComponent(query)}`)
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

document.addEventListener('click', function (e) {
  document.querySelectorAll('.kategori-suggestions').forEach(box => {
    if (!box.contains(e.target)) {
      box.classList.add('d-none');
    }
  });
});
</script>
