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
        Schema::table('apartments', function (Blueprint $table) {
            $table->decimal('base_price_eur', 10, 2)->nullable()->after('cleaning_fee');
            $table->decimal('cleaning_fee_eur', 10, 2)->nullable()->after('base_price_eur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->dropColumn('base_price_eur');
            $table->dropColumn('cleaning_fee_eur');
        });
    }
};
