<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::query()->updateOrCreate(
            ['id' => 1],
            [
                'shop_name' => 'VISTORA',
                'shop_description' => 'Katalog produk modern, cepat, dan terpercaya.',
                'shop_address' => 'Jl. Sudirman No. 1',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'phone' => '+62 21 5555 8888',
                'whatsapp' => '+62 812 3456 7890',
                'email' => 'cs@vistora.id',
                'website' => 'https://vistora.id',
                'facebook' => 'https://facebook.com/vistora.id',
                'instagram' => 'https://instagram.com/vistora.id',
                'footer_text' => 'Build with Love in Sidoarjo.',
            ]
        );
    }
}
