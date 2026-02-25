<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class ProductsPage extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'kategori')]
    public ?string $categorySlug = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategorySlug(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->categorySlug = null;
        $this->resetPage();
    }

    public function render(): View
    {
        $categories = Cache::remember(
            'public.products.categories',
            now()->addMinutes(10),
            fn () => Category::query()
                ->select('id', 'name', 'slug')
                ->orderBy('name')
                ->get()
        );

        $selectedCategory = $categories->firstWhere('slug', $this->categorySlug);

        $products = Product::query()
            ->select('id', 'category_id', 'name', 'slug', 'price', 'original_price', 'sold_count', 'view_count', 'likes_count')
            ->where('status', true)
            ->when($this->search !== '', fn ($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->when($selectedCategory, fn ($query) => $query->where('category_id', $selectedCategory->id))
            ->with([
                'category:id,name',
                'primaryImage:id,product_id,image',
            ])
            ->orderByDesc('id')
            ->paginate(8);

        return view('livewire.public.products-page', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
        ]);
    }
}
