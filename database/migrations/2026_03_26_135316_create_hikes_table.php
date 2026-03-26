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
        Schema::create('hikes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')->constrained()->cascadeOnDelete();
            
            $table->string('name_en');
            $table->string('name_cs')->nullable();
            $table->string('name_de')->nullable();

            $table->string('distance_tx_en')->nullable();
            $table->string('distance_tx_cs')->nullable();
            $table->string('distance_tx_de')->nullable();

            $table->text('description_en')->nullable();
            $table->text('description_cs')->nullable();
            $table->text('description_de')->nullable();

            $table->string('difficulty');
            $table->double('length', 8, 2);
            $table->boolean('is_for_families')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hikes');
    }
};
