<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'kasir',
        'total',
        'bayar',
        'kembali',
        'payment_type'
    ];

    public function items()
    {
        return $this->hasMany(TransaksiItem::class);
    }
}
