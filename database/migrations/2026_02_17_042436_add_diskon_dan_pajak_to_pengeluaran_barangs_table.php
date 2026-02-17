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
    $table->decimal('diskon_total', 12, 2)->default(0);
    $table->decimal('pajak', 12, 2)->default(0);
    $table->decimal('sub_total', 12, 2)->default(0);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengeluaran_barangs', function (Blueprint $table) {
            //
        });
    }
};
