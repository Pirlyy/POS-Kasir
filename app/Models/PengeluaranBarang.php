<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranBarang extends Model
{
    protected $guarded = [];

    public static function nomerpengeluaran()
    {
        $maxid = self::max('id');
        $prefix = 'trx-';
        return $prefix . date('dmy') . str_pad($maxid + 1, 5, '0', STR_PAD_LEFT);
    }

    public function items()
    {
        return $this->hasMany(
            ItemPengeluaranBarang::class,
            'pengeluaran_barang_id', // foreign key
            'id'                     // local key
        );
    }
}
