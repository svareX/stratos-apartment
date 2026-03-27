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
            $table->decimal('cleaning_fee', 10, 2)->after('base_price')->nullable()->default(600);
            $table->unsignedInteger('days_for_cleaning_fee')->after('cleaning_fee')->nullable()->default(3);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->dropColumn('cleaning_fee');
            $table->dropColumn('days_for_cleaning_fee');
        });
    }
};
