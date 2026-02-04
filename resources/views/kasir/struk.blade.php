<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk</title>
    <style>
        body {
            font-family: monospace;
            font-size: 12px;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        hr { border-top: 1px dashed #000; }
    </style>
</head>
<body onload="window.print()">

<div class="center">
    <b>POS APP</b><br>
    ====================
</div>

<p>
    Tanggal : {{ $data->created_at->format('d/m/Y H:i') }}<br>
    Kasir   : {{ $data->kasir }}
</p>

<hr>

@foreach($data->items as $item)
{{ $item->produk->nama_produk }}<br>
{{ $item->qty }} x {{ number_format($item->harga) }}
<span class="right">
    {{ number_format($item->sub_total) }}
</span><br>
@endforeach

<hr>

<p class="right">
    TOTAL : Rp {{ number_format($data->total) }}<br>
    BAYAR : Rp {{ number_format($data->bayar) }}<br>
    KEMBALI : Rp {{ number_format($data->kembali) }}
</p>

<hr>

<div class="center">
    Terima kasih 🙏
</div>

</body>
</html>
