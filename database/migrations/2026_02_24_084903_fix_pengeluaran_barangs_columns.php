    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('pengeluaran_barangs', function (Blueprint $table) {

        if (!Schema::hasColumn('pengeluaran_barangs', 'subtotal')) {
            $table->decimal('subtotal', 15, 2)->default(0);
        }

        if (!Schema::hasColumn('pengeluaran_barangs', 'diskon_transaksi')) {
            $table->decimal('diskon_transaksi', 15, 2)->default(0);
        }

        if (!Schema::hasColumn('pengeluaran_barangs', 'pajak')) {
            $table->decimal('pajak', 15, 2)->default(0);
        }

        if (!Schema::hasColumn('pengeluaran_barangs', 'total_harga')) {
            $table->decimal('total_harga', 15, 2)->default(0);
        }

        if (!Schema::hasColumn('pengeluaran_barangs', 'bayar')) {
            $table->decimal('bayar', 15, 2)->default(0);
        }

        if (!Schema::hasColumn('pengeluaran_barangs', 'kembalian')) {
            $table->decimal('kembalian', 15, 2)->default(0);
        }

        if (!Schema::hasColumn('pengeluaran_barangs', 'metode_pembayaran')) {
            $table->string('metode_pembayaran')->nullable();
        }
    });
}

public function down(): void
{
    //
}
};
