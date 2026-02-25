<footer class="bg-gray-900 text-gray-300 py-10 md:py-12 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
        <div>
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-2xl md:text-3xl font-black text-white tracking-tighter italic mb-4">
                <i class="fas fa-compass text-primary"></i> {{ $setting?->shop_name ?? 'VISTORA' }}
            </a>
            <p class="text-gray-400 mb-6 leading-relaxed text-sm">
                {{ $setting?->shop_description ?? 'Katalog produk modern, cepat, dan terpercaya.' }}
            </p>
            <div class="flex gap-3">
                <a href="#" class="w-9 h-9 rounded bg-gray-800 flex items-center justify-center hover:bg-primary hover:text-white transition"><i class="fab fa-facebook-f text-sm"></i></a>
                <a href="#" class="w-9 h-9 rounded bg-gray-800 flex items-center justify-center hover:bg-primary hover:text-white transition"><i class="fab fa-twitter text-sm"></i></a>
                <a href="#" class="w-9 h-9 rounded bg-gray-800 flex items-center justify-center hover:bg-primary hover:text-white transition"><i class="fab fa-instagram text-sm"></i></a>
            </div>
        </div>

        <div>
            <h4 class="text-white font-bold mb-4">About</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="#" class="hover:text-primary transition">Tentang Kami</a></li>
                <li><a href="#" class="hover:text-primary transition">Visi & Misi</a></li>
                <li><a href="#" class="hover:text-primary transition">Karir</a></li>
                <li><a href="#" class="hover:text-primary transition">Kebijakan Privasi</a></li>
                <li><a href="#" class="hover:text-primary transition">Syarat & Ketentuan</a></li>
            </ul>
        </div>

        <div>
            <h4 class="text-white font-bold mb-4">Kategori</h4>
            <ul class="space-y-2 text-sm">
                @foreach($categories as $category)
                    <li><a href="#" class="hover:text-primary transition">{{ $category }}</a></li>
                @endforeach
            </ul>
        </div>

        <div>
            <h4 class="text-white font-bold mb-4">Contact</h4>
            <ul class="space-y-3 text-sm">
                <li class="flex items-start gap-3">
                    <i class="fas fa-envelope text-primary mt-1"></i>
                    <span>{{ $setting?->email ?? 'cs@vistora.id' }}</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fab fa-whatsapp text-primary mt-1 text-base"></i>
                    <span>{{ $setting?->whatsapp ?? '+62 812 3456 7890' }}</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-map-marker-alt text-primary mt-1"></i>
                    <span>{{ $setting?->shop_address ?? 'Jl. Sudirman No. 1' }}, {{ $setting?->city ?? 'Jakarta' }}</span>
                </li>
            </ul>
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-3 pt-2 border-t border-white/10 text-xs md:text-sm text-gray-400 flex flex-col md:flex-row items-center justify-between gap-1">
        <p>&copy; {{ date('Y') }} {{ $setting?->shop_name ?? 'VISTORA' }}. All rights reserved.</p>
        <p>{{ $setting?->footer_text ?? 'Build with Love in Sidoarjo.' }}</p>
    </div>
</footer>
