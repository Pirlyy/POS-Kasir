@extends('layouts.shop')

@section('title','Kasir')

@section('content')
<div class="row g-3">
@foreach($products as $p)
    <div class="col-6 col-md-3 col-lg-2">
        <div class="card h-100 shadow-sm">
            <div class="bg-secondary text-white text-center py-4">
                📦
            </div>

            <div class="card-body p-2">
                <small class="fw-bold d-block">
                    {{ $p->nama_produk }}
                </small>

                <span class="text-danger fw-bold">
                    Rp {{ number_format($p->harga_jual) }}
                </span>

                <small class="text-muted d-block">
                    Stok {{ $p->stok }}
                </small>
            </div>

            <div class="card-footer bg-white border-0">
                <button class="btn btn-sm btn-outline-danger w-100"
                    onclick="addToCart(
                        {{ $p->id }},
                        '{{ $p->nama_produk }}',
                        {{ $p->harga_jual }},
                        {{ $p->stok }}
                    )">
                    + Keranjang
                </button>
            </div>
        </div>
    </div>
@endforeach
</div>
@endsection
@section('script')
<script>
let cart = {};
let total = 0;

/* OPEN CART */
function toggleCart(){
    document.getElementById('cartDrawer').classList.toggle('show');
}

/* ADD */
function addToCart(id,name,price,stock){
    if(!cart[id]){
        cart[id]={id,name,price,qty:1,stock};
    }else{
        if(cart[id].qty>=stock) return alert('Stok habis');
        cart[id].qty++;
    }
    renderCart();
}

/* RENDER */
function renderCart(){
    const el=document.getElementById('cart-items');
    el.innerHTML='';
    total=0;

    Object.values(cart).forEach(i=>{
        let sub=i.qty*i.price;
        total+=sub;

        el.innerHTML+=`
        <div class="border rounded p-2 mb-2">
            <b>${i.name}</b><br>
            <small>Rp ${i.price.toLocaleString()}</small>
            <div class="d-flex justify-content-between mt-1">
                <div>
                    <button class="btn btn-sm btn-outline-secondary"
                        onclick="i.qty--; if(i.qty<=0) delete cart[i.id]; renderCart()">−</button>
                    ${i.qty}
                    <button class="btn btn-sm btn-outline-secondary"
                        onclick="addToCart(i.id,i.name,i.price,i.stock)">+</button>
                </div>
                <b>Rp ${sub.toLocaleString()}</b>
            </div>
        </div>`;
    });

    document.getElementById('cart-total').innerText='Rp '+total.toLocaleString();
    document.getElementById('cart-count').innerText=Object.keys(cart).length;
}
</script>
@endsection
