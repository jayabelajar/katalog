<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Smartphone', 'description' => 'Koleksi smartphone terbaru.'],
            ['name' => 'Laptop & PC', 'description' => 'Laptop, desktop, dan aksesori komputer.'],
            ['name' => 'TV & Display', 'description' => 'Smart TV, monitor, dan display.'],
            ['name' => 'Audio', 'description' => 'Headset, earphone, speaker, dan audio rumah.'],
            ['name' => 'Kamera', 'description' => 'Kamera digital dan aksesorinya.'],
            ['name' => 'Gaming', 'description' => 'Perangkat gaming dan konsol.'],
            ['name' => 'Kebutuhan Harian', 'description' => 'Produk harian pilihan.'],
            ['name' => 'Furniture', 'description' => 'Furniture rumah dan kantor.'],
            ['name' => 'Fashion', 'description' => 'Pakaian, sepatu, dan aksesoris fashion.'],
            ['name' => 'Kesehatan', 'description' => 'Produk kesehatan dan perawatan tubuh.'],
            ['name' => 'Buku & Edukasi', 'description' => 'Buku dan kebutuhan pembelajaran.'],
            ['name' => 'Ibu & Anak', 'description' => 'Produk ibu hamil, bayi, dan anak.'],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['name' => $category['name']],
                [
                    'name' => $category['name'],
                    'slug' => Str::slug($category['name']),
                    'description' => $category['description'],
                ]
            );
        }
    }
}
