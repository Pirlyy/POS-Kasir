<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title','Shop')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background:#f5f5f5; }

        .navbar-shop {
            background:#ee4d2d;
        }

        .navbar-shop .nav-link,
        .navbar-shop .navbar-brand {
            color:#fff !important;
        }

        .cart-drawer {
            position: fixed;
            top: 0;
            right: -380px;
            width: 380px;
            height: 100vh;
            background: #fff;
            box-shadow: -3px 0 10px rgba(0,0,0,.2);
            transition: .3s;
            z-index: 1050;
        }

        .cart-drawer.show {
            right: 0;
        }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar navbar-shop px-4">
    <a class="navbar-brand fw-bold" href="#">🛍 TOKO KITA</a>

    <form class="flex-fill mx-3">
        <input class="form-control" placeholder="Cari produk...">
    </form>

    <button class="btn btn-light position-relative" onclick="toggleCart()">
        🛒
        <span id="cart-count"
            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            0
        </span>
    </button>
</nav>

{{-- CONTENT --}}
<div class="container-fluid mt-3">
    @yield('content')
</div>

{{-- CART DRAWER --}}
<div id="cartDrawer" class="cart-drawer">
    <div class="p-3 border-bottom d-flex justify-content-between">
        <b>Keranjang</b>
        <button class="btn btn-sm btn-danger" onclick="toggleCart()">✕</button>
    </div>

    <div id="cart-items" class="p-3"></div>

    <div class="p-3 border-top">
        <div class="d-flex justify-content-between mb-2">
            <b>Total</b>
            <b id="cart-total">Rp 0</b>
        </div>

        <!-- METODE PEMBAYARAN -->
        <select id="payment-method" class="form-select mb-2" onchange="changePayment()">
            <option value="cash">💵 Cash</option>
            <option value="qris">📱 QRIS</option>
            <option value="bank">🏦 Transfer Bank</option>
        </select>

        <!-- CASH ONLY -->
        <div id="cash-section">
            <input type="number" id="uang-bayar" class="form-control mb-2" placeholder="Uang customer">
            <input type="text" id="uang-kembali" class="form-control mb-2" placeholder="Kembalian" readonly>
        </div>

        <button class="btn btn-success w-100" onclick="submitCheckout()">
            Checkout
        </button>
    </div>
</div>

{{-- SCRIPT GLOBAL --}}
<script>
let cart = {};
let total = 0;

function toggleCart(){
    document.getElementById('cartDrawer').classList.toggle('show');
}

function addToCart(id,name,price,stock){
    if(!cart[id]){
        cart[id]={id,name,price,qty:1,stock};
    }else{
        if(cart[id].qty >= stock){
            alert('Stok habis');
            return;
        }
        cart[id].qty++;
    }
    renderCart();
}

function increaseQty(id){
    if(cart[id].qty < cart[id].stock){
        cart[id].qty++;
        renderCart();
    }
}

function decreaseQty(id){
    cart[id].qty--;
    if(cart[id].qty <= 0){
        delete cart[id];
    }
    renderCart();
}

function renderCart(){
    const el = document.getElementById('cart-items');
    el.innerHTML = '';
    total = 0;

    Object.values(cart).forEach(i=>{
        let sub = i.qty * i.price;
        total += sub;

        el.innerHTML += `
        <div class="border rounded p-2 mb-2">
            <b>${i.name}</b><br>
            <small>Rp ${i.price.toLocaleString()}</small>

            <div class="d-flex justify-content-between align-items-center mt-2">
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-secondary"
                        onclick="decreaseQty(${i.id})">−</button>

                    <span class="btn btn-sm btn-light">${i.qty}</span>

                    <button class="btn btn-sm btn-outline-secondary"
                        onclick="increaseQty(${i.id})">+</button>
                </div>

                <b>Rp ${sub.toLocaleString()}</b>
            </div>
        </div>`;
    });

    document.getElementById('cart-total').innerText = 'Rp ' + total.toLocaleString();
    document.getElementById('cart-count').innerText = Object.keys(cart).length;
}
</script>

<script>
function changePayment(){
    let method = document.getElementById('payment-method').value;
    let cashSection = document.getElementById('cash-section');

    if(method === 'cash'){
        cashSection.style.display = 'block';
        document.getElementById('uang-bayar').value = '';
        document.getElementById('uang-kembali').value = '';
    }else{
        cashSection.style.display = 'none';
        document.getElementById('uang-bayar').value = total;
        document.getElementById('uang-kembali').value = '';
    }
}
</script>

<script>
document.getElementById('uang-bayar').addEventListener('input', function(){
    let bayar = parseInt(this.value || 0);
    let kembali = bayar - total;

    document.getElementById('uang-kembali').value =
        kembali >= 0 ? 'Rp ' + kembali.toLocaleString() : '';
});
</script>

<script>
function submitCheckout(){
    let method = document.getElementById('payment-method').value;
    let bayar = parseInt(document.getElementById('uang-bayar').value || 0);

    if (Object.keys(cart).length === 0) {
        alert('Keranjang kosong');
        return;
    }

    if (method === 'cash' && bayar < total) {
        alert('Uang customer kurang');
        return;
    }

    fetch("{{ route('kasir.checkout') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            total: total,
            bayar: method === 'cash' ? bayar : total,
            payment_method: method,
            items: Object.values(cart)
        })
    })
    .then(res => res.json())
    .then(res => {
        if(res.success){
            alert('Transaksi berhasil');
            cart = {};
            renderCart();
            toggleCart();
            location.reload();
        }else{
            alert(res.message);
        }
    })
    .catch(() => alert('Checkout gagal'));
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@yield('script')

</body>
</html>
