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
            if (! Schema::hasColumn('apartments', 'type')) {
                $table->string('type')->nullable();
            }

            if (! Schema::hasColumn('apartments', 'slug')) {
                $table->string('slug')->unique()->nullable();
            }

            if (! Schema::hasColumn('apartments', 'tags')) {
                $table->json('tags')->nullable();
            }

            if (Schema::hasColumn('apartments', 'photos')) {
                $table->dropColumn('photos');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apartments', function (Blueprint $table) {
            if (! Schema::hasColumn('apartments', 'photos')) {
                $table->json('photos')->nullable();
            }

            if (Schema::hasColumn('apartments', 'tags')) {
                $table->dropColumn('tags');
            }

            if (Schema::hasColumn('apartments', 'slug')) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            }

            if (Schema::hasColumn('apartments', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
