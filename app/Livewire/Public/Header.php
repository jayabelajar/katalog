<?php

namespace App\Livewire\Public;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Component;

class Header extends Component
{
    public string $search = '';

    public int $notificationCount = 1;

    public array $categories = [];

    public array $menus = [
        ['label' => 'Homepage', 'icon' => 'fa-home', 'url' => '/', 'route' => 'home'],
        ['label' => 'Flash Deals', 'icon' => 'fa-bolt', 'url' => '#', 'route' => null],
        ['label' => 'Track Order', 'icon' => 'fa-box', 'url' => '#', 'route' => null],
        ['label' => 'Return & Refund', 'icon' => 'fa-undo', 'url' => '#', 'route' => null],
        ['label' => 'Shipping Info', 'icon' => 'fa-truck', 'url' => '#', 'route' => null],
        ['label' => 'About Us', 'icon' => 'fa-info-circle', 'url' => '#', 'route' => null],
    ];

    public function mount(): void
    {
        $this->categories = Cache::remember(
            'public.header.categories',
            now()->addMinutes(10),
            fn () => Category::query()
                ->select('name')
                ->orderBy('name')
                ->limit(12)
                ->pluck('name')
                ->all()
        );
    }

    public function clearNotifications(): void
    {
        $this->notificationCount = 0;
    }

    public function getFilteredCategoriesProperty(): array
    {
        if ($this->search === '') {
            return $this->categories;
        }

        return array_values(array_filter(
            $this->categories,
            fn (string $category) => Str::contains(Str::lower($category), Str::lower($this->search))
        ));
    }

    public function render()
    {
        $this->menus = array_map(function (array $menu): array {
            $menu['active'] = $menu['route'] ? request()->routeIs($menu['route']) : false;

            return $menu;
        }, $this->menus);

        return view('livewire.public.header');
    }
}
