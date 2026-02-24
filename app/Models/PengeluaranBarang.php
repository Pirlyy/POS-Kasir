<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranBarang extends Model
{
    protected $fillable = [
        'nomor_pengeluaran',
        'nama_petugas',
        'subtotal',
        'diskon_item',
        'diskon_transaksi',
        'pajak',
        'total_harga',
        'bayar',
        'kembalian',
        'metode_pembayaran'
    ];

    // ❌ HAPUS protected $guarded = [];

    public static function nomerpengeluaran()
    {
        $maxid = self::max('id') ?? 0;
        return 'TRX-' . date('dmy') . str_pad($maxid + 1, 5, '0', STR_PAD_LEFT);
    }

    public function items()
    {
        return $this->hasMany(
            ItemPengeluaranBarang::class,
            'pengeluaran_barang_id',
            'id'
        );
    }


    /*
    ===============================
    FORMAT RUPIAH (BONUS HELPER)
    ===============================
    */
    public function getTotalRupiahAttribute()
    {
        return 'Rp ' . number_format($this->total_harga);
    }

}
