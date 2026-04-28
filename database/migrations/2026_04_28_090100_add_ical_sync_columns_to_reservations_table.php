<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->timestamp('external_last_synced_at')->nullable()->after('external_booking_id');
            $table->index(['apartment_id', 'booking_source']);
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropIndex(['apartment_id', 'booking_source']);
            $table->dropColumn('external_last_synced_at');
        });
    }
};

