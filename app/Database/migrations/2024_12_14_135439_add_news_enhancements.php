<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('id');
            $table->boolean('public')->default(true)->after('url_slug');
            $table->string('stub')->nullable()->after('subject');
            $table->dateTime('published_at')->after('created_at');
            $table->boolean('visible')->default(false)->after('published_at'); // Enum
            $table->dateTime('deleted_at')->nullable()->after('updated_at');
        });

        // get all the news items and update the new columns
        $news = DB::table('news')->get();
        foreach ($news as $item) {
            DB::table('news')->where('id', $item->id)->update(
                [
                    'slug'         => $item->id,
                    'published_at' => $item->created_at,
                    'visible'      => true,
                    'public'       => false,
                ]
            );
        }
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
