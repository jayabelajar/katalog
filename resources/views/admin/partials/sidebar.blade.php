<aside class="w-64 bg-white border-r hidden md:block">
    <div class="p-4 font-semibold">Admin</div>
    <nav class="px-4 pb-4 space-y-2 text-sm">
        <a class="block hover:underline" href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a class="block hover:underline" href="{{ route('admin.kategori.index') }}">Kategori</a>
        <a class="block hover:underline" href="{{ route('admin.produk.index') }}">Produk</a>
        <a class="block hover:underline" href="{{ route('admin.marketplace-link.index') }}">Marketplace Link</a>
        <a class="block hover:underline" href="{{ route('admin.setting.index') }}">Setting</a>
    </nav>
</aside>