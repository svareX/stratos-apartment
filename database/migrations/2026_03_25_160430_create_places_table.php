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
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')->constrained('apartments')->onDelete('cascade');
            
            $table->string('name_en')->nullable();
            $table->string('name_cs')->nullable();
            $table->string('name_de')->nullable();
            
            $table->text('description_en')->nullable();
            $table->text('description_cs')->nullable();
            $table->text('description_de')->nullable();

            $table->string('distance_text_en')->nullable();
            $table->string('distance_text_cs')->nullable();
            $table->string('distance_text_de')->nullable();
            
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            $table->string('url')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
