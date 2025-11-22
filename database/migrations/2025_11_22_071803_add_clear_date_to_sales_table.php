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
        Schema::table('sales', function (Blueprint $table) {
            $table->date('expected_clear_date')->nullable()->after('due_amount');
            $table->date('actual_clear_date')->nullable()->after('expected_clear_date');
            $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('unpaid')->after('actual_clear_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['expected_clear_date', 'actual_clear_date', 'payment_status']);
        });
    }
};
