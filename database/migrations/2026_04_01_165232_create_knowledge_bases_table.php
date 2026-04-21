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
        if (DB::getDriverName() === 'pgsql') {
            Schema::ensureVectorExtensionExists();
        }

        Schema::create('knowledge_bases', function (Blueprint $table) {
            $table->id();
            $table->string('source_type');
            $table->string('source_id')->nullable();
            $table->text('content');
            if (DB::getDriverName() === 'pgsql') {
                $table->vector('embedding', dimensions: 768)->index();
            } else {
                // SQLite / other drivers: store embedding as nullable text (JSON or CSV of floats)
                $table->text('embedding')->nullable();
            }
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_bases');
    }
};
