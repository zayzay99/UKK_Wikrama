<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('transactions', 'fine_reason')) {
                $table->string('fine_reason')->nullable()->after('status');
            }

            if (! Schema::hasColumn('transactions', 'fine_days')) {
                $table->unsignedInteger('fine_days')->default(0)->after('fine_reason');
            }

            if (! Schema::hasColumn('transactions', 'fine_rate')) {
                $table->decimal('fine_rate', 12, 2)->default(0)->after('fine_days');
            }

            if (! Schema::hasColumn('transactions', 'fine_amount')) {
                $table->decimal('fine_amount', 12, 2)->default(0)->after('fine_rate');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $columns = [];

            foreach (['fine_reason', 'fine_days', 'fine_rate', 'fine_amount'] as $column) {
                if (Schema::hasColumn('transactions', $column)) {
                    $columns[] = $column;
                }
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
