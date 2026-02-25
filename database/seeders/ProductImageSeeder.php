<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::query()->get(['id', 'slug']);

        foreach ($products as $product) {
            ProductImage::query()->updateOrCreate(
                [
                    'product_id' => $product->id,
                    'is_primary' => true,
                ],
                [
                    'image' => "https://picsum.photos/seed/{$product->slug}/800/800",
                ]
            );
        }
    }
}
