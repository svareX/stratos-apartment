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
        Schema::table('photos', function (Blueprint $table) {
            $table->string('tag_en')->nullable()->after('tag');
            $table->string('tag_cs')->nullable()->after('tag_en');
            $table->string('tag_de')->nullable()->after('tag_cs');
        });

        DB::table('photos')->whereNotNull('tag')->update([
            'tag_en' => DB::raw('tag'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->dropColumn('tag_de');
            $table->dropColumn('tag_cs');
            $table->dropColumn('tag_en');
        });
    }
};
