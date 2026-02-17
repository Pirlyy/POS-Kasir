<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Struk</title>

<style>
body{
    font-family: monospace;
    font-size:12px;
    width:260px;
    margin:auto;
}

.center{text-align:center}
.right{text-align:right}

hr{
    border:none;
    border-top:1px dashed #000;
    margin:6px 0;
}

.row{
    display:flex;
    justify-content:space-between;
}

.bold{font-weight:bold}
.small{font-size:11px}
.total{
    font-size:13px;
    font-weight:bold;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="center bold">POS APP</div>
<div class="center small">==============================</div>

<p class="small">
No Transaksi : {{ $pengeluaran->nomor_pengeluaran ?? '-' }}<br>
Tanggal : {{ $pengeluaran->created_at?->format('d/m/Y H:i') ?? '-' }}<br>
Kasir : {{ $pengeluaran->nama_petugas ?? '-' }}
</p>

<hr>

<!-- LIST PRODUK -->
@foreach($pengeluaran->items ?? [] as $item)

<div>{{ $item->product->nama_produk ?? '-' }}</div>

<div class="row small">
<span>
{{ $item->jumlah ?? 0 }} x Rp {{ number_format($item->harga_jual ?? 0,0,',','.') }}
</span>

<span>
Rp {{ number_format($item->sub_total ?? 0,0,',','.') }}
</span>
</div>

@endforeach

<hr>

<!-- =========================
RINGKASAN PERHITUNGAN
========================= -->

{{-- SUBTOTAL --}}
<div class="row">
<span>Subtotal</span>
<span>Rp {{ number_format($pengeluaran->subtotal ?? 0,0,',','.') }}</span>
</div>

{{-- DISKON PER PRODUK --}}
@if(($pengeluaran->diskon_item ?? 0) > 0)
<div class="row">
<span>Diskon Produk</span>
<span>- Rp {{ number_format($pengeluaran->diskon_item,0,',','.') }}</span>
</div>
@endif

{{-- DISKON TRANSAKSI --}}
@if(($pengeluaran->diskon_transaksi ?? 0) > 0)
<div class="row">
<span>Diskon Belanja</span>
<span>- Rp {{ number_format($pengeluaran->diskon_transaksi,0,',','.') }}</span>
</div>
@endif

{{-- PAJAK --}}
@if(($pengeluaran->pajak ?? 0) > 0)
<div class="row">
<span>Pajak (11%)</span>
<span>Rp {{ number_format($pengeluaran->pajak,0,',','.') }}</span>
</div>
@endif

<hr>

<!-- TOTAL -->
<div class="row total">
<span>TOTAL</span>
<span>Rp {{ number_format($pengeluaran->total_harga ?? 0,0,',','.') }}</span>
</div>

<div class="row">
<span>BAYAR</span>
<span>Rp {{ number_format($pengeluaran->bayar ?? 0,0,',','.') }}</span>
</div>

<div class="row">
<span>KEMBALI</span>
<span>Rp {{ number_format($pengeluaran->kembalian ?? 0,0,',','.') }}</span>
</div>

<hr>

<div class="center small">
Terima kasih<br>
Barang yang sudah dibeli tidak dapat dikembalikan
</div>

<script>
window.onload = function () {
    window.print();
    setTimeout(() => window.close(), 500);
};
</script>

</body>
</html>
