<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    <nav class="text-sm text-gray-500">
        <a href="{{ route('home') }}" class="hover:text-primary">Homepage</a>
        <span class="mx-2">/</span>
        <a href="{{ route('katalog') }}" class="hover:text-primary">Produk</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">{{ $product->name }}</span>
    </nav>

    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10">
        <div class="space-y-4">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden" x-data="productGallery(@js($galleryImages))" x-init="startAutoSlide()">
                <template x-if="slides.length">
                    <div class="relative">
                        <img :src="slides[currentSlide]" alt="{{ $product->name }}" class="w-full h-[340px] md:h-[540px] object-cover transition-all duration-300">

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
        </div>

        <div class="space-y-5">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-5 md:p-7">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <span class="inline-flex items-center bg-primary-light text-primary-dark px-3 py-1 rounded-full text-xs font-semibold mb-3">{{ $product->category?->name }}</span>
                        <h1 class="text-xl md:text-3xl font-black text-gray-900 leading-tight">{{ $product->name }}</h1>
                    </div>
                    <span class="bg-white/95 text-red-500 text-[11px] font-bold px-3 py-1.5 rounded-full border border-red-100 flex items-center gap-2 shrink-0">
                        <i class="fas fa-heart"></i> {{ number_format($product->likes_count) }}
                        <span class="text-gray-400">|</span>
                        <i class="far fa-eye"></i> {{ number_format($product->view_count) }}
                    </span>
                </div>

                <div class="mb-6">
                    @if($product->original_price)
                        <p class="text-gray-400 line-through text-sm">Rp {{ number_format((float) $product->original_price, 0, ',', '.') }}</p>
                    @endif
                    <p class="text-primary font-black text-2xl md:text-4xl">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                </div>

                <div class="space-y-3">
                    <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Beli di Marketplace</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ $marketplaceLinks['shopee'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-bold text-sm bg-orange-500 text-white hover:bg-orange-600 transition">
                            <i class="fas fa-store"></i> Shopee
                        </a>
                        <a href="{{ $marketplaceLinks['tokopedia'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-bold text-sm bg-emerald-600 text-white hover:bg-emerald-700 transition">
                            <i class="fas fa-leaf"></i> Tokopedia
                        </a>
                        <a href="{{ $marketplaceLinks['lazada'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-bold text-sm bg-blue-600 text-white hover:bg-blue-700 transition">
                            <i class="fas fa-bag-shopping"></i> Lazada
                        </a>
                        <a href="{{ $marketplaceLinks['blibli'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-bold text-sm bg-sky-500 text-white hover:bg-sky-600 transition">
                            <i class="fas fa-cart-shopping"></i> Blibli
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-5 md:p-7">
                <h3 class="text-base md:text-lg font-bold text-gray-800 mb-2">Deskripsi Produk</h3>
                <p class="text-sm md:text-base text-gray-600 leading-relaxed">{{ $product->description ?: 'Belum ada deskripsi produk.' }}</p>
            </div>
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
