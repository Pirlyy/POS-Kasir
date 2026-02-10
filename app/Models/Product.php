<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'nama_produk',
        'harga_jual',
        'harga_beli_pokok',
        'kategori_id',
        'stok',
        'stok_minimal',
        'is_active',
        'sku',
        'image',
    ];

    public static function nomorSku(){
        // SKU-0001
        $prefix = 'SKU-';
        $maxId = self::max('id');
        $sku = $prefix . str_pad($maxId + 1, 5, '0', STR_PAD_LEFT);
        return $sku;
    }

    // 🔑 RELASI INI WAJIB
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
