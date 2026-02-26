<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('rating_avg', 2, 1)->unsigned()->default(0)->after('likes_count');
            $table->unsignedInteger('rating_count')->default(0)->after('rating_avg');
            $table->index(['status', 'rating_avg']);
        });

        DB::table('products')
            ->where('likes_count', '>', 0)
            ->update([
                'rating_count' => DB::raw('likes_count'),
                'rating_avg' => DB::raw('LEAST(5.0, ROUND(4.0 + (likes_count / 500), 1))'),
            ]);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['status', 'rating_avg']);
            $table->dropColumn(['rating_avg', 'rating_count']);
        });
    }
};
