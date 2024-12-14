<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('url_slug')->nullable()->after('id');
            $table->boolean('public')->default(true)->after('url_slug');
            $table->string('short_description')->nullable()->after('subject');
            $table->dateTime('published_at')->after('body');
            $table->boolean('visible')->default(false)->after('published_at'); // Enum
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('url_slug');
            $table->dropColumn('public');
            $table->dropColumn('short_description');
            $table->dropColumn('published_at');
            $table->dropColumn('visible');
        });
    }
};
