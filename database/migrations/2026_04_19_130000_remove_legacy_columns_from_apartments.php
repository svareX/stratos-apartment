<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->dropColumn(['name', 'description', 'tags']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
        });

        DB::table('apartments')->update([
            'name' => DB::raw('name_en'),
            'description' => DB::raw('description_en'),
            'tags' => DB::raw('tags_en'),
        ]);
    }
};
