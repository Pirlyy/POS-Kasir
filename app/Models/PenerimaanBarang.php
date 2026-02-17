<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerimaanBarang extends Model
{
    protected $guarded = ['id'];

    // ✅ HARUS STATIC
    public static function nomorPenerimaan()
{
    $today = now()->format('Ymd');

    $last = self::whereDate('created_at', today())
        ->latest()
        ->first();

    $urutan = $last ? ((int) substr($last->nomor_penerimaan, -4)) + 1 : 1;

    return 'IN-' . $today . '-' . str_pad($urutan, 4, '0', STR_PAD_LEFT);
}


    public function items(){
        return $this->hasMany(ItemPenerimaanBarang::class, 'nomor_penerimaan', 'nomor_penerimaan');
    }
}
