<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Samsung Galaxy S25 Ultra 512GB', 'category' => 'Smartphone', 'price' => 19500000, 'original_price' => 22000000, 'sold_count' => 120, 'view_count' => 1200, 'likes_count' => 245, 'is_featured' => true],
            ['name' => 'iPhone 17 Pro 256GB', 'category' => 'Smartphone', 'price' => 20999000, 'original_price' => 22999000, 'sold_count' => 98, 'view_count' => 1450, 'likes_count' => 198, 'is_featured' => true],
            ['name' => 'ASUS ROG Zephyrus G16 RTX 4070', 'category' => 'Laptop & PC', 'price' => 31200000, 'original_price' => 34000000, 'sold_count' => 87, 'view_count' => 980, 'likes_count' => 176, 'is_featured' => true],
            ['name' => 'MacBook Pro M4 14 Inch', 'category' => 'Laptop & PC', 'price' => 33999000, 'original_price' => 35999000, 'sold_count' => 76, 'view_count' => 890, 'likes_count' => 151, 'is_featured' => false],
            ['name' => 'Sony WH-1000XM6', 'category' => 'Audio', 'price' => 5650000, 'original_price' => 6300000, 'sold_count' => 143, 'view_count' => 1800, 'likes_count' => 321, 'is_featured' => true],
            ['name' => 'JBL Charge 6', 'category' => 'Audio', 'price' => 2699000, 'original_price' => 2999000, 'sold_count' => 132, 'view_count' => 920, 'likes_count' => 210, 'is_featured' => false],
            ['name' => 'LG OLED evo C4 65 Inch 4K', 'category' => 'TV & Display', 'price' => 24850000, 'original_price' => 27000000, 'sold_count' => 64, 'view_count' => 720, 'likes_count' => 174, 'is_featured' => true],
            ['name' => 'Samsung Odyssey G8 32 Inch', 'category' => 'TV & Display', 'price' => 10999000, 'original_price' => 11999000, 'sold_count' => 58, 'view_count' => 640, 'likes_count' => 167, 'is_featured' => false],
            ['name' => 'Sony Alpha A7 IV', 'category' => 'Kamera', 'price' => 32999000, 'original_price' => 34999000, 'sold_count' => 39, 'view_count' => 510, 'likes_count' => 112, 'is_featured' => false],
            ['name' => 'Canon EOS R8 Body', 'category' => 'Kamera', 'price' => 21999000, 'original_price' => 23499000, 'sold_count' => 44, 'view_count' => 560, 'likes_count' => 121, 'is_featured' => false],
            ['name' => 'PlayStation 5 Slim', 'category' => 'Gaming', 'price' => 8499000, 'original_price' => 8999000, 'sold_count' => 92, 'view_count' => 1300, 'likes_count' => 255, 'is_featured' => true],
            ['name' => 'Nintendo Switch 2', 'category' => 'Gaming', 'price' => 6999000, 'original_price' => 7499000, 'sold_count' => 101, 'view_count' => 1110, 'likes_count' => 279, 'is_featured' => true],
        ];

        $categoryIds = Category::query()->pluck('id', 'name');

        foreach ($products as $item) {
            $name = $item['name'];
            $slug = Str::slug($name);

            Product::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $categoryIds[$item['category']] ?? null,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => "Produk {$name} dengan kualitas terbaik dan garansi resmi.",
                    'price' => $item['price'],
                    'original_price' => $item['original_price'],
                    'status' => true,
                    'sold_count' => $item['sold_count'],
                    'view_count' => $item['view_count'],
                    'likes_count' => $item['likes_count'],
                    'rating_count' => $item['likes_count'],
                    'rating_avg' => min(5, round(4 + ($item['likes_count'] / 500), 1)),
                    'is_featured' => $item['is_featured'],
                ]
            );
        }
    }
}

