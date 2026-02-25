<?php

namespace Database\Seeders;

use App\Models\MarketplaceLink;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MarketplaceLinkSeeder extends Seeder
{
    public function run(): void
    {
        $marketplaces = ['Shopee', 'Tokopedia', 'Lazada', 'Blibli'];
        $products = Product::query()->get(['id', 'slug']);

        foreach ($products as $product) {
            foreach ($marketplaces as $marketplace) {
                MarketplaceLink::query()->updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'marketplace' => $marketplace,
                    ],
                    [
                        'url' => "https://example.com/" . Str::lower($marketplace) . "/{$product->slug}",
                    ]
                );
            }
        }
    }
}
