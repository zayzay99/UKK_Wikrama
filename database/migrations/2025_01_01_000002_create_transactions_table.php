<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siswa
            $table->foreignId('item_id')->constrained();
            $table->foreignId('officer_id')->constrained('users'); // Petugas/Admin
            $table->date('borrow_date');
            $table->date('return_date');
            $table->date('actual_return_date')->nullable();
            $table->enum('status', ['dipinjam', 'kembali', 'hilang', 'rusak'])->default('dipinjam');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('transactions'); }
};