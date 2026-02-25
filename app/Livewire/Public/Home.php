<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductLike;
use App\Models\ProductView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Component;

class Home extends Component
{
    public string $visitorToken;

    public array $likedProductIds = [];

    public bool $hasTrackedViews = false;

    public function mount(): void
    {
        $this->visitorToken = session('visitor_token', (string) Str::uuid());
        session(['visitor_token' => $this->visitorToken]);

        $this->likedProductIds = ProductLike::query()
            ->where('visitor_token', $this->visitorToken)
            ->pluck('product_id')
            ->all();
    }

    public function toggleLike(int $productId): void
    {
        $product = Product::query()->select('id')->find($productId);

        if (! $product) {
            return;
        }

        $like = ProductLike::query()
            ->where('product_id', $productId)
            ->where('visitor_token', $this->visitorToken)
            ->first();

        if ($like) {
            $like->delete();

            Product::query()
                ->whereKey($productId)
                ->where('likes_count', '>', 0)
                ->decrement('likes_count');

            $this->likedProductIds = array_values(array_filter(
                $this->likedProductIds,
                fn (int $likedProductId) => $likedProductId !== $productId
            ));

            return;
        }

        ProductLike::query()->create([
            'product_id' => $productId,
            'visitor_token' => $this->visitorToken,
            'ip_address' => request()->ip(),
            'user_agent' => Str::limit((string) request()->userAgent(), 255, ''),
        ]);

        Product::query()->whereKey($productId)->increment('likes_count');

        $this->likedProductIds[] = $productId;
        $this->likedProductIds = array_values(array_unique($this->likedProductIds));
    }

    public function render(): View
    {
        $popularCategories = Cache::remember(
            'public.home.popular_categories',
            now()->addMinutes(10),
            fn () => Category::query()
                ->select('id', 'name', 'slug')
                ->withCount([
                    'products as active_products_count' => fn ($query) => $query->where('status', true),
                ])
                ->orderByDesc('active_products_count')
                ->limit(12)
                ->get()
        );

        $bestSellerProducts = Product::query()
            ->select('id', 'category_id', 'name', 'slug', 'price', 'original_price', 'sold_count', 'view_count', 'likes_count')
            ->where('status', true)
            ->with([
                'category:id,name',
                'primaryImage:id,product_id,image',
            ])
            ->orderByDesc('sold_count')
            ->limit(4)
            ->get();

        $flashSaleProducts = Product::query()
            ->select('id', 'category_id', 'name', 'slug', 'price', 'original_price', 'sold_count', 'view_count', 'likes_count')
            ->where('status', true)
            ->where(function ($query) {
                $query->where('is_featured', true)
                    ->orWhereColumn('original_price', '>', 'price');
            })
            ->with([
                'category:id,name',
                'primaryImage:id,product_id,image',
            ])
            ->orderByRaw('(original_price - price) DESC')
            ->orderByDesc('sold_count')
            ->limit(4)
            ->get();

        $newProducts = Product::query()
            ->select('id', 'category_id', 'name', 'slug', 'price', 'original_price', 'sold_count', 'view_count', 'likes_count')
            ->where('status', true)
            ->with([
                'category:id,name',
                'primaryImage:id,product_id,image',
            ])
            ->latest('id')
            ->limit(8)
            ->get();

        if (! $this->hasTrackedViews) {
            $trackedProductIds = $bestSellerProducts
                ->pluck('id')
                ->merge($flashSaleProducts->pluck('id'))
                ->merge($newProducts->pluck('id'))
                ->unique()
                ->values();

            $this->trackProductViews($trackedProductIds);
            $this->hasTrackedViews = true;

            $bestSellerProducts->each(function (Product $product) use ($trackedProductIds): void {
                if ($trackedProductIds->contains($product->id)) {
                    $product->view_count++;
                }
            });

            $flashSaleProducts->each(function (Product $product) use ($trackedProductIds): void {
                if ($trackedProductIds->contains($product->id)) {
                    $product->view_count++;
                }
            });

            $newProducts->each(function (Product $product) use ($trackedProductIds): void {
                if ($trackedProductIds->contains($product->id)) {
                    $product->view_count++;
                }
            });
        }

        return view('livewire.public.home', [
            'popularCategories' => $popularCategories,
            'bestSellerProducts' => $bestSellerProducts,
            'flashSaleProducts' => $flashSaleProducts,
            'newProducts' => $newProducts,
        ]);
    }

    private function trackProductViews(Collection $productIds): void
    {
        if ($productIds->isEmpty()) {
            return;
        }

        $now = now();

        $existingViews = ProductView::query()
            ->where('visitor_token', $this->visitorToken)
            ->whereIn('product_id', $productIds)
            ->get()
            ->keyBy('product_id');

        foreach ($productIds as $productId) {
            $existing = $existingViews->get($productId);

            if ($existing) {
                $existing->increment('view_count');
                $existing->update([
                    'last_viewed_at' => $now,
                    'ip_address' => request()->ip(),
                    'user_agent' => Str::limit((string) request()->userAgent(), 255, ''),
                ]);

                continue;
            }

            ProductView::query()->create([
                'product_id' => $productId,
                'visitor_token' => $this->visitorToken,
                'view_count' => 1,
                'last_viewed_at' => $now,
                'ip_address' => request()->ip(),
                'user_agent' => Str::limit((string) request()->userAgent(), 255, ''),
            ]);
        }

        Product::query()->whereIn('id', $productIds)->increment('view_count');
    }
}
