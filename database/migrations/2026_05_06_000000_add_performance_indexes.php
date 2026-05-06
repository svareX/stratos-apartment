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
        Schema::table('reservations', function (Blueprint $table) {
            $table->index(['apartment_id', 'status', 'check_in', 'check_out'], 'reservations_apartment_status_dates_idx');
            $table->index(['apartment_id', 'booking_source'], 'reservations_apartment_booking_source_idx');
        });

        Schema::table('photos', function (Blueprint $table) {
            $table->index(['apartment_id', 'position'], 'photos_apartment_position_idx');
            $table->index(['apartment_id', 'is_main'], 'photos_apartment_is_main_idx');
        });

        Schema::table('instagram_posts', function (Blueprint $table) {
            $table->index('posted_at', 'instagram_posts_posted_at_idx');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['score', 'created_at'], 'reviews_score_created_idx');
        });

        Schema::table('frequently_asked_questions', function (Blueprint $table) {
            $table->index(['is_active', 'position'], 'faqs_active_position_idx');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropIndex('reservations_apartment_status_dates_idx');
            $table->dropIndex('reservations_apartment_booking_source_idx');
        });

        Schema::table('photos', function (Blueprint $table) {
            $table->dropIndex('photos_apartment_position_idx');
            $table->dropIndex('photos_apartment_is_main_idx');
        });

        Schema::table('instagram_posts', function (Blueprint $table) {
            $table->dropIndex('instagram_posts_posted_at_idx');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('reviews_score_created_idx');
        });

        Schema::table('frequently_asked_questions', function (Blueprint $table) {
            $table->dropIndex('faqs_active_position_idx');
        });
    }
};
