<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    @php
        $isPromo = (float) $product->original_price > (float) $product->price;
        $discountPercent = $isPromo ? (int) round((((float) $product->original_price - (float) $product->price) / (float) $product->original_price) * 100) : 0;
        $compactViews = fn ($value) => $value >= 1000 ? floor($value / 1000) . 'k' : number_format($value);
    @endphp

    <nav class="text-sm">
        <div class="flex flex-wrap items-center gap-2 text-gray-500">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-blue-100 hover:border-primary hover:text-primary transition">
                <i class="fas fa-house text-xs"></i> Homepage
            </a>
            <span>/</span>
            <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-blue-100 hover:border-primary hover:text-primary transition">
                <i class="fas fa-box-open text-xs"></i> Produk
            </a>
            <span>/</span>
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary-light text-primary-dark border border-primary/10">
                <i class="fas fa-tag text-xs"></i> {{ $product->category?->name }}
            </span>
            <span>/</span>
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-700 text-white border border-blue-700">
                <i class="fas fa-cube text-xs"></i> {{ $product->name }}
            </span>
        </div>
    </nav>

    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10">
        <div class="space-y-4">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden" x-data="productGallery(@js($galleryImages))" x-init="startAutoSlide()">
                <template x-if="slides.length">
                    <div class="relative">
                        <img :src="slides[currentSlide]" alt="{{ $product->name }}" class="w-full h-[340px] md:h-[540px] object-cover transition-all duration-300">
                        @if($isPromo)
                            <span class="absolute top-3 left-3 bg-gradient-to-r from-blue-500 to-blue-700 text-white text-[11px] font-bold px-3 py-1.5 rounded-full shadow">
                                FLASH SALE -{{ $discountPercent }}%
                            </span>
                        @endif

                        <button @click="prev()" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 text-white hover:bg-black/60 transition flex items-center justify-center">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </button>
                        <button @click="next()" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 text-white hover:bg-black/60 transition flex items-center justify-center">
                            <i class="fas fa-chevron-right text-sm"></i>
                        </button>

                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2">
                            <template x-for="(_, index) in slides" :key="index">
                                <button @click="goTo(index)" :class="currentSlide === index ? 'w-6 bg-white' : 'w-2 bg-white/60'" class="h-2 rounded-full transition-all"></button>
                            </template>
                        </div>
                    </div>
                </template>

                <template x-if="!slides.length">
                    <div class="w-full h-[340px] md:h-[540px] bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-box text-6xl text-gray-300"></i>
                    </div>
                </template>
            </div>

            <div class="hidden lg:block bg-white rounded-3xl border border-gray-100 shadow-sm p-5 md:p-7">
                <h3 class="text-base md:text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-600"><i class="fas fa-share-nodes text-xs"></i></span>
                    Share Produk
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="https://wa.me/?text={{ urlencode(route('produk.detail', $product->slug)) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold text-sm bg-blue-50 text-blue-700 border border-blue-100 hover:bg-blue-100 transition">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('produk.detail', $product->slug)) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold text-sm bg-blue-50 text-blue-700 border border-blue-100 hover:bg-blue-100 transition">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('produk.detail', $product->slug)) }}&text={{ urlencode($product->name) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold text-sm bg-blue-50 text-blue-700 border border-blue-100 hover:bg-blue-100 transition">
                        <i class="fab fa-twitter"></i> X / Twitter
                    </a>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ route('produk.detail', $product->slug) }}')" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold text-sm bg-blue-600 text-white hover:bg-blue-700 transition">
                        <i class="fas fa-link"></i> Copy Link
                    </button>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-5 md:p-7">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <span class="inline-flex items-center gap-2 bg-primary-light text-primary-dark px-3 py-1 rounded-full text-xs font-semibold mb-3">{{ $product->category?->name }} <span class="text-blue-700">|</span> Terjual {{ number_format($product->sold_count) }}</span>
                        <h1 class="text-xl md:text-3xl font-black text-gray-900 leading-tight">{{ $product->name }}</h1>
                    </div>
                    <span class="bg-white/95 text-blue-600 text-[11px] font-bold px-3 py-1.5 rounded-full border border-blue-100 flex items-center gap-2 shrink-0">
                        <i class="fas fa-heart"></i> {{ number_format($product->likes_count) }}
                        <span class="text-gray-400">|</span>
                        <i class="far fa-eye"></i> {{ $compactViews($product->view_count) }}
                    </span>
                </div>

                <div class="mb-6">
                    @if($product->original_price)
                        <p class="text-gray-400 line-through text-sm">Rp {{ number_format((float) $product->original_price, 0, ',', '.') }}</p>
                    @endif
                    <p class="text-primary font-black text-2xl md:text-4xl">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                </div>

                <div class="space-y-3">
                    <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wide flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600"><i class="fas fa-store text-xs"></i></span>
                        Beli di Marketplace
                    </h2>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ $marketplaceLinks['shopee'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-bold text-sm bg-blue-500 text-white hover:bg-blue-600 transition">
                            <i class="fas fa-store"></i> Shopee
                        </a>
                        <a href="{{ $marketplaceLinks['tokopedia'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-bold text-sm bg-blue-600 text-white hover:bg-blue-700 transition">
                            <i class="fas fa-leaf"></i> Tokopedia
                        </a>
                        <a href="{{ $marketplaceLinks['lazada'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-bold text-sm bg-blue-700 text-white hover:bg-blue-800 transition">
                            <i class="fas fa-bag-shopping"></i> Lazada
                        </a>
                        <a href="{{ $marketplaceLinks['blibli'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-bold text-sm bg-blue-800 text-white hover:bg-blue-900 transition">
                            <i class="fas fa-cart-shopping"></i> Blibli
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-5 md:p-7">
                <h3 class="text-base md:text-lg font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-600"><i class="fas fa-align-left text-xs"></i></span>
                    Deskripsi Produk
                </h3>
                <p class="text-sm md:text-base text-gray-600 leading-relaxed">{{ $product->description ?: 'Belum ada deskripsi produk.' }}</p>
            </div>

            <div class="lg:hidden bg-white rounded-3xl border border-gray-100 shadow-sm p-5 md:p-7">
                <h3 class="text-base md:text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-600"><i class="fas fa-share-nodes text-xs"></i></span>
                    Share Produk
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="https://wa.me/?text={{ urlencode(route('produk.detail', $product->slug)) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold text-sm bg-blue-50 text-blue-700 border border-blue-100 hover:bg-blue-100 transition">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('produk.detail', $product->slug)) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold text-sm bg-blue-50 text-blue-700 border border-blue-100 hover:bg-blue-100 transition">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('produk.detail', $product->slug)) }}&text={{ urlencode($product->name) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold text-sm bg-blue-50 text-blue-700 border border-blue-100 hover:bg-blue-100 transition">
                        <i class="fab fa-twitter"></i> X / Twitter
                    </a>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ route('produk.detail', $product->slug) }}')" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold text-sm bg-blue-600 text-white hover:bg-blue-700 transition">
                        <i class="fas fa-link"></i> Copy Link
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600"><i class="fas fa-link text-sm"></i></span>
                Produk Terkait
            </h2>
            <span class="text-xs md:text-sm text-gray-500">Kategori {{ $product->category?->name }}</span>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            @forelse($relatedProducts as $item)
                <a href="{{ route('produk.detail', $item->slug) }}" class="bg-white rounded-xl p-3 md:p-4 border border-gray-100 shadow-sm hover:shadow-md transition flex flex-col">
                    <div class="bg-gray-100 rounded-lg h-32 md:h-44 w-full overflow-hidden mb-3 relative">
                        @if($item->primaryImage?->image)
                            <img src="{{ $item->primaryImage->image }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center"><i class="fas fa-box text-4xl text-gray-300"></i></div>
                        @endif
                        <span class="absolute top-2 right-2 bg-white/95 text-blue-600 text-[10px] font-bold px-2 py-1 rounded-full flex items-center gap-2">
                            <i class="fas fa-heart"></i> {{ number_format($item->likes_count) }}
                            <span class="text-gray-500">|</span>
                            <i class="far fa-eye"></i> {{ $compactViews($item->view_count) }}
                        </span>
                    </div>
                    <h3 class="font-bold text-gray-800 text-sm md:text-base mb-1">{{ $item->name }}</h3>
                    <div class="mt-auto">
                        @if($item->original_price)
                            <p class="text-gray-400 line-through text-[10px] md:text-xs">Rp {{ number_format((float) $item->original_price, 0, ',', '.') }}</p>
                        @endif
                        <p class="text-primary font-black text-sm md:text-lg">Rp {{ number_format((float) $item->price, 0, ',', '.') }}</p>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-sm text-gray-500">Belum ada produk terkait pada kategori ini.</div>
            @endforelse
        </div>
    </section>
</div>

@push('scripts')
<script>
function productGallery(images) {
    return {
        slides: Array.isArray(images) ? images : [],
        currentSlide: 0,
        intervalId: null,
        startAutoSlide() {
            if (this.slides.length <= 1) return;
            this.intervalId = setInterval(() => {
                this.next();
            }, 4000);
        },
        stopAutoSlide() {
            if (this.intervalId) {
                clearInterval(this.intervalId);
                this.intervalId = null;
            }
        },
        next() {
            if (!this.slides.length) return;
            this.currentSlide = (this.currentSlide + 1) % this.slides.length;
        },
        prev() {
            if (!this.slides.length) return;
            this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        },
        goTo(index) {
            this.currentSlide = index;
            this.stopAutoSlide();
            this.startAutoSlide();
        }
    }
}
</script>
@endpush
