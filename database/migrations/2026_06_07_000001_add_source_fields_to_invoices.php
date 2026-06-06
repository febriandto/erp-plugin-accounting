<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Dua kolom ini adalah "jembatan integrasi" antar plugin.
            // Accounting tidak perlu tahu tentang tabel plugin lain —
            // cukup simpan "dari mana invoice ini berasal".
            //
            // Contoh:
            //   source_type = 'purchase_order'
            //   source_id   = 42  (id di tabel purchase_orders)
            //
            // Kalau invoice dibuat manual oleh user, kedua kolom ini NULL.
            $table->string('source_type')->nullable()->after('notes');
            $table->unsignedBigInteger('source_id')->nullable()->after('source_type');

            // Index untuk mempercepat query "cari invoice berdasarkan sumber"
            $table->index(['source_type', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['source_type', 'source_id']);
            $table->dropColumn(['source_type', 'source_id']);
        });
    }
};
