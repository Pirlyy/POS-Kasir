@extends('layouts.app-plain')

@section('content')
<div class="d-flex" style="height:calc(100vh - 120px)">

    {{-- LEFT : PRODUK --}}
    <div class="flex-fill p-3 bg-light">
        <h4 class="mb-3">🧾 Kasir POS</h4>

        <div class="row">
            @foreach($products as $p)
            <div class="col-md-3 mb-3">
                <button type="button"
                        class="btn btn-light w-100 border product-btn"
                        data-id="{{ $p->id }}"
                        data-name="{{ $p->nama_produk }}"
                        data-price="{{ $p->harga_jual }}"
                        data-stock="{{ $p->stok }}">

                    <div class="bg-secondary text-white d-flex
                                align-items-center justify-content-center mb-2"
                         style="height:80px">📦</div>

                    <b>{{ $p->nama_produk }}</b><br>
                    <small>Rp {{ number_format($p->harga_jual) }}</small><br>
                    <small class="text-muted">Stok {{ $p->stok }}</small>
                </button>
            </div>
            @endforeach
        </div>
    </div>

    {{-- RIGHT : KERANJANG --}}
    <div style="width:380px" class="border-left d-flex flex-column bg-white">

        <div class="p-3 border-bottom">
            <h5>🛒 Keranjang</h5>
        </div>

        <div id="cart-items" class="flex-fill p-3 overflow-auto">
            <p class="text-muted text-center">Keranjang kosong</p>
        </div>

        <div class="p-3 border-top">

            <div class="d-flex justify-content-between mb-2">
                <b>Total</b>
                <b id="cart-total">Rp 0</b>
            </div>

            <div class="btn-group w-100 mb-2">
                <button type="button" class="btn btn-success" onclick="pilihCash()">💵 Cash</button>
                <button type="button" class="btn btn-secondary" onclick="pilihQris()">📱 QRIS</button>
            </div>

            <input type="number" id="uang-bayar"
                   class="form-control mb-2"
                   placeholder="Uang customer">

            <input type="text" id="uang-kembali"
                   class="form-control mb-2"
                   placeholder="Kembalian" readonly>

            <button type="button" class="btn btn-primary w-100"
                    onclick="submitTransaksi()">
                💾 Simpan & Cetak
            </button>
        </div>
    </div>
</div>

{{-- MIDTRANS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
let cart = {};
let total = 0;
let paymentType = 'cash';
let printWindow = null;

/* ================= TAMBAH PRODUK ================= */
document.querySelectorAll('.product-btn').forEach(btn => {
    btn.onclick = () => {
        const id = btn.dataset.id;

        if (!cart[id]) {
            cart[id] = {
                id,
                name: btn.dataset.name,
                price: Number(btn.dataset.price),
                qty: 1,
                stock: Number(btn.dataset.stock)
            };
        } else {
            if (cart[id].qty >= cart[id].stock) {
                alert('Stok habis');
                return;
            }
            cart[id].qty++;
        }
        renderCart();
    };
});

/* ================= RENDER CART ================= */
function renderCart(){
    const el = document.getElementById('cart-items');
    el.innerHTML = '';
    total = 0;

    if (!Object.keys(cart).length) {
        el.innerHTML = '<p class="text-muted text-center">Keranjang kosong</p>';
        document.getElementById('cart-total').innerText = 'Rp 0';
        hitungKembalian();
        return;
    }

    Object.values(cart).forEach(i => {
        const sub = i.qty * i.price;
        total += sub;

        el.innerHTML += `
        <div class="border rounded p-2 mb-2">
            <b>${i.name}</b><br>
            <small>Rp ${i.price.toLocaleString()}</small>
            <div class="d-flex justify-content-between mt-1 align-items-center">
                <div>
                    <button onclick="kurang(${i.id})">−</button>
                    <span class="mx-2">${i.qty}</span>
                    <button onclick="tambah(${i.id})">+</button>
                </div>
                <b>Rp ${sub.toLocaleString()}</b>
            </div>
        </div>`;
    });

    document.getElementById('cart-total').innerText =
        'Rp ' + total.toLocaleString();

    hitungKembalian();
}

function tambah(id){
    if (cart[id].qty >= cart[id].stock) return alert('Stok habis');
    cart[id].qty++;
    renderCart();
}

function kurang(id){
    cart[id].qty--;
    if (cart[id].qty <= 0) delete cart[id];
    renderCart();
}

/* ================= PAYMENT ================= */
function pilihCash(){
    paymentType = 'cash';
    document.getElementById('uang-bayar').disabled = false;
    hitungKembalian();
}

function pilihQris(){
    paymentType = 'qris';
    document.getElementById('uang-bayar').value = '';
    document.getElementById('uang-bayar').disabled = true;
    document.getElementById('uang-kembali').value = 'Rp 0';
}

/* ================= KEMBALIAN ================= */
function hitungKembalian(){
    const bayar = Number(document.getElementById('uang-bayar').value || 0);
    const kembali = bayar - total;

    document.getElementById('uang-kembali').value =
        'Rp ' + Math.max(kembali, 0).toLocaleString();
}

document.getElementById('uang-bayar')
    .addEventListener('input', hitungKembalian);

/* ================= SUBMIT ================= */
function submitTransaksi(){
    if (total <= 0) return alert('Keranjang kosong');

    // BUKA TAB DARI USER CLICK (ANTI BLOCK)
    printWindow = window.open('', '_blank');

    if (paymentType === 'qris') {
        fetch("{{ route('kasir.midtrans.token') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ total })
        })
        .then(r => r.json())
        .then(r => {
            snap.pay(r.token, {
                onSuccess: function () {
                    simpanTransaksi();
                }
            });
        });
    } else {
        simpanTransaksi();
    }
}

/* ================= SIMPAN KE SERVER ================= */
function simpanTransaksi(){
    fetch("{{ route('pengeluaran-barang.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            bayar: paymentType === 'cash'
                ? document.getElementById('uang-bayar').value
                : total,
            payment_type: paymentType,
            produk: Object.values(cart).map(i => ({
                produk_id: i.id,
                qty: i.qty,
                sub_total: i.qty * i.price
            }))
        })
    })
    .then(r => r.json())
    .then(r => {
        // PASTI KE ROUTE KASIR (BUKAN ADMIN)
        printWindow.location.href =
            "{{ url('/kasir/pengeluaran') }}/" + r.id + "/print";
        location.reload();
    });
}
</script>
@endsection
