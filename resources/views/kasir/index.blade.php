@extends('layouts.app')

@section('content')
<form onsubmit="return false;">
@csrf

<div class="d-flex" style="height:calc(100vh - 80px)">

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

                    <div style="height:80px" class="bg-secondary text-white d-flex align-items-center justify-content-center mb-2">
                        📦
                    </div>

                    <b>{{ $p->nama_produk }}</b><br>
                    <small>Rp {{ number_format($p->harga_jual) }}</small><br>
                    <small class="text-muted">Stok {{ $p->stok }}</small>
                </button>
            </div>
            @endforeach
        </div>
    </div>

    {{-- RIGHT : KERANJANG --}}
    <div style="width:380px" class="border-left d-flex flex-column">

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

            {{-- METODE --}}
            <div class="btn-group w-100 mb-2">
                <button class="btn btn-success" onclick="pilihCash()">💵 Cash</button>
                <button class="btn btn-secondary" onclick="pilihQris()">📱 QRIS</button>
            </div>

            {{-- CASH --}}
            <input type="number" id="uang-bayar" class="form-control mb-2" placeholder="Uang customer">
            <input type="text" id="uang-kembali" class="form-control mb-2" placeholder="Kembalian" readonly>

            <button class="btn btn-primary w-100" onclick="submitTransaksi()">
                💾 Simpan Transaksi
            </button>

        </div>
    </div>
</div>
</form>

{{-- MIDTRANS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
 data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
let cart = {};
let total = 0;
let paymentType = 'cash';

/* ===== TAMBAH PRODUK ===== */
document.querySelectorAll('.product-btn').forEach(btn=>{
    btn.onclick=()=>{
        const id=btn.dataset.id;
        if(!cart[id]){
            cart[id]={
                id,
                name:btn.dataset.name,
                price:+btn.dataset.price,
                qty:1,
                stock:+btn.dataset.stock
            };
        }else{
            if(cart[id].qty>=cart[id].stock) return alert('Stok habis');
            cart[id].qty++;
        }
        renderCart();
    }
});

/* ===== RENDER CART ===== */
function renderCart(){
    const el=document.getElementById('cart-items');
    el.innerHTML='';
    total=0;

    if(!Object.keys(cart).length){
        el.innerHTML='<p class="text-muted text-center">Keranjang kosong</p>';
        document.getElementById('cart-total').innerText='Rp 0';
        return;
    }

    Object.values(cart).forEach(i=>{
        const sub=i.qty*i.price;
        total+=sub;

        el.innerHTML+=`
        <div class="border rounded p-2 mb-2">
            <div class="d-flex justify-content-between">
                <div>
                    <b>${i.name}</b><br>
                    <small>Rp ${i.price.toLocaleString()}</small>

                    <div class="mt-1">
                        <button class="btn btn-sm btn-outline-secondary"
                            onclick="kurangQty(${i.id})">−</button>
                        <span class="mx-2">${i.qty}</span>
                        <button class="btn btn-sm btn-outline-secondary"
                            onclick="tambahQty(${i.id})">+</button>
                    </div>
                </div>

                <div class="text-right">
                    <b>Rp ${sub.toLocaleString()}</b><br>
                    <button class="btn btn-sm btn-danger mt-1"
                        onclick="hapusItem(${i.id})">✕</button>
                </div>
            </div>
        </div>`;
    });

    document.getElementById('cart-total').innerText='Rp '+total.toLocaleString();
    hitungKembalian();
}

/* ===== QTY ===== */
function tambahQty(id){
    if(cart[id].qty>=cart[id].stock) return alert('Stok habis');
    cart[id].qty++;
    renderCart();
}

function kurangQty(id){
    cart[id].qty--;
    if(cart[id].qty<=0) delete cart[id];
    renderCart();
}

function hapusItem(id){
    delete cart[id];
    renderCart();
}

/* ===== CASH / QRIS ===== */
function pilihCash(){
    paymentType='cash';
    document.getElementById('uang-bayar').disabled=false;
}

function pilihQris(){
    paymentType='qris';
    document.getElementById('uang-bayar').value='';
    document.getElementById('uang-bayar').disabled=true;
    document.getElementById('uang-kembali').value='';
}

/* ===== KEMBALIAN ===== */
function hitungKembalian(){
    const bayar=+document.getElementById('uang-bayar').value||0;
    document.getElementById('uang-kembali').value=
        'Rp '+Math.max(bayar-total,0).toLocaleString();
}
document.getElementById('uang-bayar').addEventListener('input',hitungKembalian);

/* ===== SUBMIT ===== */
function submitTransaksi(){
    if(total<=0) return alert('Keranjang kosong');

    if(paymentType==='qris'){
        fetch("{{ route('kasir.midtrans.token') }}",{
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            },
            body:JSON.stringify({total})
        })
        .then(r=>r.json())
        .then(d=>{
            snap.pay(d.token,{
                onSuccess:()=>submitKeServer()
            });
        });
    }else{
        submitKeServer();
    }
}

function submitKeServer(){
    const f=document.createElement('form');
    f.method='POST';
    f.action="{{ route('pengeluaran-barang.store') }}";

    let items='';
    Object.values(cart).forEach((i,x)=>{
        items+=`
        <input name="produk[${x}][produk_id]" value="${i.id}">
        <input name="produk[${x}][qty]" value="${i.qty}">
        <input name="produk[${x}][sub_total]" value="${i.qty*i.price}">
        `;
    });

    f.innerHTML=`
        @csrf
        ${items}
        <input name="total" value="${total}">
        <input name="bayar" value="${document.getElementById('uang-bayar').value}">
        <input name="payment_type" value="${paymentType}">
    `;
    document.body.appendChild(f);
    f.submit();
}
</script>
@endsection
