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
        Schema::table('profit_realizations', function (Blueprint $table) {
            $table->string('payment_voucher_number')->nullable()->after('payment_amount');
            $table->index('payment_voucher_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profit_realizations', function (Blueprint $table) {
            $table->dropIndex(['payment_voucher_number']);
            $table->dropColumn('payment_voucher_number');
        });
    }
};
