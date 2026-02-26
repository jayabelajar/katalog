<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MarketplaceLink;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with(['category:id,name', 'marketplaceLinks:id,product_id,marketplace,url'])
            ->latest('id')
            ->paginate(10);

        $categories = Category::query()->orderBy('name')->get(['id', 'name']);
        $marketplaceOptions = ['Shopee', 'Tokopedia', 'Lazada', 'Blibli'];
        $editProduct = null;

        if ($request->filled('edit')) {
            $editProduct = Product::query()
                ->with('marketplaceLinks:id,product_id,marketplace,url')
                ->find($request->integer('edit'));
        }

        return view('admin.products.index', compact('products', 'categories', 'marketplaceOptions', 'editProduct'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.produk.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePayload($request);
        $links = $data['marketplace_links'] ?? [];
        unset($data['marketplace_links']);

        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['name']);

        DB::transaction(function () use ($data, $links) {
            $product = Product::query()->create($data);
            $this->syncMarketplaceLinks($product, $links);
        });

        return redirect()->route('admin.produk.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $produk): RedirectResponse
    {
        return redirect()->route('admin.produk.edit', $produk);
    }

    public function edit(Product $produk): RedirectResponse
    {
        return redirect()->route('admin.produk.index', ['edit' => $produk->id]);
    }

    public function update(Request $request, Product $produk): RedirectResponse
    {
        $data = $this->validatePayload($request, $produk->id);
        $links = $data['marketplace_links'] ?? [];
        unset($data['marketplace_links']);

        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['name'], $produk->id);

        DB::transaction(function () use ($produk, $data, $links) {
            $produk->update($data);
            $this->syncMarketplaceLinks($produk, $links);
        });

        return redirect()->route('admin.produk.index')->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $produk): RedirectResponse
    {
        $produk->delete();

        return redirect()->route('admin.produk.index')->with('status', 'Produk berhasil dihapus.');
    }

    private function validatePayload(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($ignoreId)],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'boolean'],
            'sold_count' => ['required', 'integer', 'min:0'],
            'view_count' => ['required', 'integer', 'min:0'],
            'likes_count' => ['required', 'integer', 'min:0'],
            'rating_avg' => ['required', 'numeric', 'min:0', 'max:5'],
            'rating_count' => ['required', 'integer', 'min:0'],
            'is_featured' => ['required', 'boolean'],
            'marketplace_links' => ['nullable', 'array'],
            'marketplace_links.*.marketplace' => ['nullable', 'string', 'max:100'],
            'marketplace_links.*.url' => ['nullable', 'url', 'max:2000'],
        ]);
    }

    private function makeUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'produk';
        $slug = $base;
        $counter = 1;

        while (
            Product::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }

    private function syncMarketplaceLinks(Product $product, array $links): void
    {
        $normalized = collect($links)
            ->map(function ($item) {
                return [
                    'marketplace' => trim((string) ($item['marketplace'] ?? '')),
                    'url' => trim((string) ($item['url'] ?? '')),
                ];
            })
            ->filter(fn ($item) => $item['marketplace'] !== '' && $item['url'] !== '')
            ->unique('marketplace')
            ->values();

        $keepMarketplaces = $normalized->pluck('marketplace')->all();

        $product->marketplaceLinks()
            ->whereNotIn('marketplace', $keepMarketplaces ?: ['__none__'])
            ->delete();

        foreach ($normalized as $item) {
            MarketplaceLink::query()->updateOrCreate(
                [
                    'product_id' => $product->id,
                    'marketplace' => $item['marketplace'],
                ],
                ['url' => $item['url']]
            );
        }
    }
}
