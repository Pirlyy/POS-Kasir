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

.bold{
    font-weight:bold;
}

.small{
    font-size:11px;
}

.total{
    font-size:13px;
    font-weight:bold;
}

</style>
</head>

<body>

<!-- HEADER -->
<div class="center bold">
POS APP
</div>

<div class="center small">
==============================
</div>

<p class="small">
No Transaksi : {{ $pengeluaran->nomor_pengeluaran ?? '-' }}<br>
Tanggal      : {{ $pengeluaran->created_at->format('d/m/Y H:i') }}<br>
Kasir        : {{ $pengeluaran->nama_petugas }}
</p>

<hr>

<!-- LIST PRODUK -->
@foreach($pengeluaran->items as $item)

<div>
{{ $item->product->nama_produk }}
</div>

<div class="row small">
<span>
{{ $item->jumlah }} x Rp {{ number_format($item->harga_jual,0,',','.') }}
</span>

<span>
Rp {{ number_format($item->sub_total,0,',','.') }}
</span>
</div>

@endforeach

<hr>

<!-- TOTAL -->
<div class="row total">
<span>TOTAL</span>
<span>Rp {{ number_format($pengeluaran->total_harga,0,',','.') }}</span>
</div>

<div class="row">
<span>BAYAR</span>
<span>Rp {{ number_format($pengeluaran->bayar,0,',','.') }}</span>
</div>

<div class="row">
<span>KEMBALI</span>
<span>Rp {{ number_format($pengeluaran->kembalian,0,',','.') }}</span>
</div>

<hr>

<div class="center small">
Terima kasih<br>
Barang yang sudah dibeli tidak dapat dikembalikan
</div>

<script>
/*
Auto print + auto close tab
(Biar POS experience seperti minimarket)
*/
window.onload = function () {
    window.print();

    setTimeout(() => {
        window.close();
    }, 500);
};


/*
==============================
SIMPAN TRANSAKSI (AJAX)
==============================
*/
function simpanTransaksi(){

    fetch("{{ route('pengeluaran-barang.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({

            bayar: paymentType === 'cash'
                ? document.getElementById('uang-bayar').value
                : total,

            metode_pembayaran: paymentType,

            // ⭐ TAMBAH DISKON TRANSAKSI (INPUT MANUAL)
            diskon_transaksi: parseInt(
                document.getElementById('diskon-transaksi')?.value || 0
            ),

            // ⭐ PRODUK + DISKON PER ITEM
            produk: Object.values(cart).map(i => ({
                produk_id: i.id,
                qty: i.qty,
                diskon_item: i.diskon_item || 0
            }))
        })
    })

    .then(async res => {
        if (!res.ok) {
            const text = await res.text();
            throw new Error(text);
        }
        return res.json();
    })

    .then(data => {

        if (!data.id) {
            alert('Gagal simpan transaksi');
            if(printWindow) printWindow.close();
            return;
        }

        const printUrl =
            "{{ route('pengeluaran-barang.print', ':id') }}"
                .replace(':id', data.id);

        if(printWindow){
            printWindow.location.href = printUrl;
        }else{
            window.open(printUrl, '_blank');
        }
    })

    .catch(err => {
        console.error(err);
        alert('Terjadi error saat transaksi');
        if(printWindow) printWindow.close();
    });
}

</script>
</body>
</html>