<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['buku', 'alat']);
            $table->string('code')->unique(); // Untuk Barcode/KTP/ID
            $table->integer('stock');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('items'); }
};