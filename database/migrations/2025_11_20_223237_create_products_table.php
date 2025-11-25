<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Relasi ke Vendor (Pemilik Produk)
            // vendor_id mengacu ke tabel 'vendors' (tabel terpisah)
            $table->foreignId('vendor_id')
                  ->constrained('vendors')
                  ->onDelete('cascade'); // Jika vendor dihapus, produknya juga dihapus

            // Relasi ke Category
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->onDelete('restrict'); // Mencegah penghapusan kategori jika masih ada produk

            // Detail Produk
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock');
            $table->text('description')->nullable();
            
            // Kolom untuk path gambar
            $table->string('image_path')->nullable(); 
            
            // Status produk: active, draft, out_of_stock
            $table->enum('status', ['active', 'draft', 'out_of_stock'])->default('draft');

            $table->timestamps();
            
            // Soft Deletes (untuk requirement proyek)
            $table->softDeletes();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};