<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->text('external_ical_url')->nullable()->after('active');
            $table->string('ical_export_token', 64)->nullable()->unique()->after('external_ical_url');
        });
    }

    public function down(): void
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->dropUnique(['ical_export_token']);
            $table->dropColumn(['external_ical_url', 'ical_export_token']);
        });
    }
};
