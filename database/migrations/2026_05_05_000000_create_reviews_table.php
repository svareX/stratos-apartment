<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('source')->default('local');
            $table->string('external_id')->nullable()->index();
            $table->unsignedBigInteger('hotel_id')->nullable()->index();
            $table->string('author_name')->nullable();
            $table->string('locale')->nullable();
            $table->string('language')->nullable();
            $table->string('customer_type')->nullable();
            $table->integer('score')->nullable();

            $table->text('title_en')->nullable();
            $table->text('title_cs')->nullable();
            $table->text('title_de')->nullable();

            $table->text('content_en')->nullable();
            $table->text('content_cs')->nullable();
            $table->text('content_de')->nullable();

            $table->timestamp('reviewed_at')->nullable();
            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
