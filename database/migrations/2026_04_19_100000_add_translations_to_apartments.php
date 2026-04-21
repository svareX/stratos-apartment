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
            $table->string('name_en')->nullable()->after('name');
            $table->string('name_cs')->nullable()->after('name_en');
            $table->string('name_de')->nullable()->after('name_cs');

            $table->text('description_en')->nullable()->after('description');
            $table->text('description_cs')->nullable()->after('description_en');
            $table->text('description_de')->nullable()->after('description_cs');

            $table->json('tags_en')->nullable()->after('tags');
            $table->json('tags_cs')->nullable()->after('tags_en');
            $table->json('tags_de')->nullable()->after('tags_cs');
        });

        DB::table('apartments')->update([
            'name_en' => DB::raw('name'),
            'description_en' => DB::raw('description'),
            'tags_en' => DB::raw('tags'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->dropColumn('tags_de');
            $table->dropColumn('tags_cs');
            $table->dropColumn('tags_en');

            $table->dropColumn('description_de');
            $table->dropColumn('description_cs');
            $table->dropColumn('description_en');

            $table->dropColumn('name_de');
            $table->dropColumn('name_cs');
            $table->dropColumn('name_en');
        });
    }
};
