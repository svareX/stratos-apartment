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
        Schema::table('photos', function (Blueprint $table) {
            $table->boolean('is_new')->default(false);
            $table->string('title_en')->nullable();
            $table->string('title_cs')->nullable();
            $table->string('title_de')->nullable();
            $table->string('highlighted_title_en')->nullable();
            $table->string('highlighted_title_cs')->nullable();
            $table->string('highlighted_title_de')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_cs')->nullable();
            $table->text('description_de')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->dropColumn([
                'is_new',
                'title_en', 'title_cs', 'title_de',
                'highlighted_title_en', 'highlighted_title_cs', 'highlighted_title_de',
                'description_en', 'description_cs', 'description_de',
            ]);
        });
    }
};
