<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('pengeluaran_barangs', function (Blueprint $table) {

        if (!Schema::hasColumn('pengeluaran_barangs', 'subtotal')) {
            $table->decimal('subtotal', 12, 2)->default(0);
        }

        if (!Schema::hasColumn('pengeluaran_barangs', 'diskon_item')) {
            $table->decimal('diskon_item', 12, 2)->default(0);
        }

        if (!Schema::hasColumn('pengeluaran_barangs', 'diskon_transaksi')) {
            $table->decimal('diskon_transaksi', 12, 2)->default(0);
        }

        if (!Schema::hasColumn('pengeluaran_barangs', 'pajak')) {
            $table->decimal('pajak', 12, 2)->default(0);
        }
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
