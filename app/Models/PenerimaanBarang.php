<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerimaanBarang extends Model
{
    protected $guarded = ['id'];

    // ✅ HARUS STATIC
    public static function nomorPenerimaan()
    {
        $max = self::max('id') ?? 0; // antisipasi data kosong
        $prefix = 'PBR-';
        $date = date('dmy');

        $nomor = $prefix . $date . str_pad($max + 1, 4, '0', STR_PAD_LEFT);

        return $nomor;
    }

    public function items(){
        return $this->hasMany(ItemPenerimaanBarang::class, 'nomor_penerimaan', 'nomor_penerimaan');
    }
}
