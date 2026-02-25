<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class CategoryDetailPage extends Component
{
    use WithPagination;

    public string $slug;

    public Category $category;

    #[Url(as: 'q')]
    public string $search = '';

    public function mount(string $slug): void
    {
        $this->slug = $slug;
        $this->category = Category::query()
            ->select('id', 'name', 'slug', 'description')
            ->where('slug', $this->slug)
            ->firstOrFail();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $products = Product::query()
            ->select('id', 'category_id', 'name', 'slug', 'price', 'original_price', 'sold_count', 'view_count', 'likes_count')
            ->where('status', true)
            ->where('category_id', $this->category->id)
            ->when($this->search !== '', fn ($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->with(['primaryImage:id,product_id,image'])
            ->orderByDesc('id')
            ->paginate(8);

        return view('livewire.public.category-detail-page', [
            'products' => $products,
        ]);
    }
}
