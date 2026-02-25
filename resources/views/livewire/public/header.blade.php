<header x-data="{ mobileMenu: false, mobileSearch: false }" class="fixed top-0 left-0 right-0 bg-white border-b z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between gap-3 sm:gap-6">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-2xl font-black text-primary tracking-tighter italic">
            <i class="fas fa-compass"></i> VISTORA
        </a>

        <div x-data="{ open: false }" class="relative hidden md:block">
            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 text-gray-700 hover:text-primary font-bold">
                <i class="fas fa-layer-group text-xs"></i>
                Kategori
                <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div x-show="open" x-transition class="absolute top-full left-0 mt-2 w-52 bg-white border rounded-2xl shadow-lg py-2 z-50">
                @foreach($categories as $category)
                    <a href="#" class="block px-4 py-2 hover:bg-gray-50 text-sm text-gray-700">{{ $category }}</a>
                @endforeach
                <a href="{{ route('katalog') }}" class="block px-4 py-2 hover:bg-gray-50 text-sm font-semibold text-primary">Explore kategori</a>
            </div>
        </div>

        <div class="hidden md:block flex-1 max-w-2xl relative" wire:click.stop>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search any products" class="w-full bg-gray-50 border border-gray-200 focus:border-primary focus:bg-white rounded-full py-2.5 pl-5 pr-12 outline-none transition text-sm">
            <button class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary" title="Cari">
                <i class="fas fa-search"></i>
            </button>

            @if($search !== '')
                <div class="absolute left-0 right-0 mt-2 bg-white border border-gray-100 rounded-2xl shadow-lg z-40 overflow-hidden">
                    @forelse($this->filteredCategories as $item)
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">{{ $item }}</a>
                    @empty
                        <div class="px-4 py-3 text-sm text-gray-500">Tidak ada kategori yang cocok.</div>
                    @endforelse
                </div>
            @endif
        </div>

        <div class="flex items-center gap-3 sm:gap-4">
            <button
                @click="mobileSearch = !mobileSearch; if (mobileSearch) { $nextTick(() => $refs.mobileSearchInput.focus()) }"
                class="mobile-only header-icon-btn relative"
                title="Cari"
            >
                <i class="fas fa-search text-lg"></i>
            </button>
            <button class="desktop-only header-icon-btn relative" title="Flash Sale">
                <i class="fas fa-bolt text-lg sm:text-xl text-orange-500 blink"></i>
                <span class="icon-dot blink"></span>
            </button>
            <button wire:click="clearNotifications" class="header-icon-btn relative" title="Notifikasi">
                <i class="far fa-bell text-lg sm:text-xl"></i>
                @if($notificationCount > 0)
                    <span class="icon-dot blink"></span>
                @endif
            </button>
            <button @click="mobileMenu = true; mobileSearch = false" class="mobile-only header-icon-btn relative" title="Menu">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <button class="desktop-only header-icon-btn relative" title="Customer Service">
                <i class="fas fa-headset text-lg sm:text-xl"></i>
            </button>
            <div class="w-px h-6 bg-gray-300 mx-1 sm:mx-2 hidden md:block"></div>
            <a href="#" class="hidden lg:flex items-center gap-2.5 bg-primary text-white px-8 py-3 rounded-xl font-bold text-sm leading-none hover:bg-primary-dark transition shadow-sm">
                <i class="fas fa-download text-xs"></i>
                <span>Unduh App</span>
            </a>
        </div>
    </div>

    <div x-cloak x-show="mobileSearch" class="md:hidden border-t border-gray-100 px-4 py-3 bg-white">
        <div class="relative">
            <input
                x-ref="mobileSearchInput"
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Search any products"
                class="w-full bg-gray-50 border border-gray-200 focus:border-primary focus:bg-white rounded-full py-2.5 pl-5 pr-10 outline-none transition text-sm"
            >
            <button @click="mobileSearch = false" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary" title="Tutup Pencarian">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @if($search !== '')
            <div class="mt-2 bg-white border border-gray-100 rounded-2xl shadow-lg overflow-hidden">
                @forelse($this->filteredCategories as $item)
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">{{ $item }}</a>
                @empty
                    <div class="px-4 py-3 text-sm text-gray-500">Tidak ada kategori yang cocok.</div>
                @endforelse
            </div>
        @endif
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 overflow-x-auto flex items-center gap-8 text-base font-bold text-gray-700 border-t hide-scrollbar">
        @foreach($menus as $menu)
            <a href="{{ $menu['url'] }}" class="{{ $menu['active'] ? 'text-primary' : 'hover:text-primary' }} whitespace-nowrap flex items-center gap-2.5">
                <i class="fas {{ $menu['icon'] }} text-base {{ $menu['active'] ? 'text-primary' : 'text-gray-500' }}"></i>
                {{ $menu['label'] }}
            </a>
        @endforeach
    </div>

    <div x-cloak x-show="mobileMenu" class="md:hidden fixed inset-0 z-[60]">
        <div @click="mobileMenu = false" class="absolute inset-0 bg-black/40"></div>
        <aside class="absolute left-0 top-0 h-full w-72 bg-white shadow-xl p-5 overflow-y-auto">
            <div class="flex items-center justify-between mb-5">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-black text-primary tracking-tight italic">
                    <i class="fas fa-compass"></i> VISTORA
                </a>
                <button @click="mobileMenu = false" class="header-icon-btn !w-9 !h-9"><i class="fas fa-times"></i></button>
            </div>

            <div class="mb-6">
                <h4 class="text-xs font-bold uppercase text-gray-400 mb-2">Kategori</h4>
                <div class="space-y-1">
                    @php
                        $categoryIcons = ['fa-mobile-screen', 'fa-shirt', 'fa-headphones', 'fa-couch', 'fa-mobile-alt'];
                    @endphp
                    @foreach($categories as $category)
                        <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas {{ $categoryIcons[$loop->index] ?? 'fa-box' }} text-primary w-4 text-center"></i>
                            <span>{{ $category }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <h4 class="text-xs font-bold uppercase text-gray-400 mb-2">Navigasi</h4>
                <div class="space-y-1">
                    @foreach($menus as $menu)
                        <a href="{{ $menu['url'] }}" class="block px-3 py-2 rounded-xl text-sm {{ $menu['active'] ? 'text-primary bg-primary-light font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                            <i class="fas {{ $menu['icon'] }} w-5"></i> {{ $menu['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100">
                <a href="#" class="w-full inline-flex items-center justify-center gap-2 bg-primary text-white px-4 py-3 rounded-xl font-semibold text-sm hover:bg-primary-dark transition">
                    <i class="fas fa-download text-xs"></i>
                    <span>Unduh App</span>
                </a>
            </div>
        </aside>
    </div>
</header>
