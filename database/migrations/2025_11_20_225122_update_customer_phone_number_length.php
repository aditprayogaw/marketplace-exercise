<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Ubah panjang kolom, misalnya menjadi 25 untuk sangat aman
            $table->string('phone_number', 25)->nullable()->change(); 
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Kembalikan ke panjang sebelumnya jika diperlukan
            $table->string('phone_number', 15)->nullable()->change(); 
        });
    }
};