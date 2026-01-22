<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('item_pengeluaran_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengeluaran_barang_id')
                  ->constrained('pengeluaran_barangs')
                  ->cascadeOnDelete();

            $table->foreignId('product_id')
                  ->constrained('products');

            $table->integer('jumlah');
            $table->decimal('harga_jual', 15, 2);
            $table->decimal('sub_total', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_pengeluaran_barangs');
    }
};
