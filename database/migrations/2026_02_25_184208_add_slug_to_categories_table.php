<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        $categories = DB::table('categories')->select('id', 'name')->get();
        $usedSlugs = [];

        foreach ($categories as $category) {
            $baseSlug = Str::slug($category->name);
            $slug = $baseSlug;
            $counter = 1;

            while (in_array($slug, $usedSlugs, true)) {
                $slug = $baseSlug . '-' . $counter++;
            }

            $usedSlugs[] = $slug;

            DB::table('categories')
                ->where('id', $category->id)
                ->update(['slug' => $slug]);
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->unique('slug');
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
