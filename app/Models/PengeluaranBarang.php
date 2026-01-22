<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranBarang extends Model
{
    protected $guarded = ['$id'];
    public static function nomerpengeluaran()
    {
        $maxid = self::max('id');
        $prefix = 'trx-';
        $nomor = $prefix . date('dmy') . str_pad($maxid + 1, 5, '0', STR_PAD_LEFT);
        return $nomor;
    }
      // ✅ INI YANG KURANG
    public function itemPengeluaranBarang()
    {
        return $this->hasMany(
            ItemPengeluaranBarang::class,
            'pengeluaran_barang_id'
        );
    }
}
