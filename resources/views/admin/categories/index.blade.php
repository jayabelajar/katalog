@extends('admin.layouts.app')

@section('title', 'Kategori')
@section('header', 'Kategori')

@section('content')
    <div
        class="space-y-4"
        x-data="{
            drawerOpen: {{ ($editCategory || $errors->any()) ? 'true' : 'false' }},
            isEdit: {{ $editCategory ? 'true' : 'false' }},
            editId: @js(old('edit_id', $editCategory->id ?? null)),
            form: {
                name: @js(old('name', $editCategory->name ?? '')),
                slug: @js(old('slug', $editCategory->slug ?? '')),
                description: @js(old('description', $editCategory->description ?? '')),
            },
            storeUrl: @js(route('admin.kategori.store')),
            updateUrlTemplate: @js(route('admin.kategori.update', ['kategori' => '__ID__'])),
            openCreate() {
                this.drawerOpen = true;
                this.isEdit = false;
                this.editId = null;
                this.form = { name: '', slug: '', description: '' };
            },
            openEdit(item) {
                this.drawerOpen = true;
                this.isEdit = true;
                this.editId = item.id;
                this.form = {
                    name: item.name ?? '',
                    slug: item.slug ?? '',
                    description: item.description ?? '',
                };
            },
            closeDrawer() {
                this.drawerOpen = false;
            },
            get actionUrl() {
                return this.isEdit
                    ? this.updateUrlTemplate.replace('__ID__', this.editId)
                    : this.storeUrl;
            }
        }"
    >
        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm font-semibold">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="rounded-xl border border-rose-200 bg-rose-50 text-rose-700 px-4 py-3 text-sm font-semibold">
                {{ session('error') }}
            </div>
        @endif

        <div class="space-y-4">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Kategori</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kelola kategori produk sesuai data seed.</p>
                </div>
                <button type="button" @click="openCreate()" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
                    <i class="ti ti-plus text-base"></i> Kategori Baru
                </button>
            </div>

            <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
                <div class="w-full overflow-x-auto">
                    <table class="w-full min-w-[760px] lg:min-w-0 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800/60 text-gray-600 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-3 text-left">Nama</th>
                                <th class="px-4 py-3 text-left">Slug</th>
                                <th class="px-4 py-3 text-left">Deskripsi</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($categories as $category)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ $category->name }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $category->slug }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ \Illuminate\Support\Str::limit($category->description, 80) }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <button
                                                type="button"
                                                data-id="{{ $category->id }}"
                                                data-name="{{ $category->name }}"
                                                data-slug="{{ $category->slug }}"
                                                data-description="{{ $category->description }}"
                                                @click="openEdit({ id: Number($el.dataset.id), name: $el.dataset.name, slug: $el.dataset.slug, description: $el.dataset.description })"
                                                class="inline-flex items-center rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-1.5 text-xs font-semibold hover:bg-gray-50 dark:hover:bg-gray-800"
                                            >
                                                Edit
                                            </button>
                                            <form method="POST" action="{{ route('admin.kategori.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-10 text-center text-gray-500">Belum ada data kategori.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>

        <div x-show="drawerOpen" x-cloak x-transition.opacity class="fixed inset-0 bg-black/40 z-40" @click="closeDrawer()"></div>
        <aside
            class="fixed top-0 right-0 h-screen w-full sm:max-w-md bg-white dark:bg-gray-900 border-l border-gray-200 dark:border-gray-800 z-50 shadow-2xl overflow-y-auto"
            x-show="drawerOpen"
            x-cloak
            x-transition:enter="transform transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
        >
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">
                        <span x-text="isEdit ? 'Edit Kategori' : 'Tambah Kategori'"></span>
                    </h3>
                    <button type="button" class="w-9 h-9 rounded-lg border border-gray-200 dark:border-gray-700 inline-flex items-center justify-center" @click="closeDrawer()">
                        <i class="ti ti-x text-lg"></i>
                    </button>
                </div>
                
                    <form method="POST" :action="actionUrl" class="space-y-4">
                        @csrf
                        <template x-if="isEdit">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <input type="hidden" name="edit_id" :value="editId ?? ''">

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                            <input type="text" name="name" x-model="form.name" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Slug</label>
                            <input type="text" name="slug" x-model="form.slug" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                            <textarea name="description" rows="4" x-model="form.description" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100"></textarea>
                        </div>
                        @if ($errors->any())
                            <ul class="text-xs text-rose-600 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <div class="flex items-center gap-2">
                            <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
                                <span x-text="isEdit ? 'Update' : 'Simpan'"></span>
                            </button>
                            <button type="button" x-show="isEdit" @click="closeDrawer()" class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-800">
                                    Batal
                            </button>
                        </div>
                    </form>
            </div>
        </aside>
    </div>
@endsection
