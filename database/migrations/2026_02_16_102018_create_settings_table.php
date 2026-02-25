<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->string('shop_name');
            $table->string('shop_logo')->nullable();      // path logo
            $table->text('shop_description')->nullable();
            $table->text('shop_address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();

            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();

            $table->string('footer_text')->nullable();
            $table->string('favicon')->nullable();        // path favicon

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
