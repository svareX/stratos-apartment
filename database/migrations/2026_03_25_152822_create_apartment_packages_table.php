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
        Schema::create('apartment_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')->constrained('apartments')->onDelete('cascade');
            
            $table->string('name_en')->nullable();
            $table->string('name_cs')->nullable();
            $table->string('name_de')->nullable();
            
            $table->json('features')->nullable();
            
            $table->double('price', 8, 2)->default(0);
            $table->string('icon')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartment_packages');
    }
};
