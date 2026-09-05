<div class="flex gap-6">

    {{-- Sidebar Filter (desktop) --}}
    <aside class="hidden w-56 shrink-0 md:block">
        <div class="sticky top-20 space-y-5">
            <div>
                <h2 class="text-lg font-bold text-zinc-900">Filter</h2>
                <p class="text-xs text-zinc-500">Spesifikasi Produk</p>
            </div>

            <nav class="space-y-1 text-sm">
                <button wire:click="$set('category', 'CUSTOM_SERVICE')"
                        class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left transition
                            {{ $category === 'CUSTOM_SERVICE' ? 'bg-zinc-100 font-semibold text-zinc-900' : 'text-zinc-500 hover:bg-zinc-50' }}">
                    <flux:icon.printer class="h-4 w-4" />
                    Jasa Cetak
                </button>
                <button wire:click="$set('category', 'PHYSICAL_PRODUCT')"
                        class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left transition
                            {{ $category === 'PHYSICAL_PRODUCT' ? 'bg-zinc-100 font-semibold text-zinc-900' : 'text-zinc-500 hover:bg-zinc-50' }}">
                    <flux:icon.archive-box class="h-4 w-4" />
                    Produk Fisik
                </button>

                <div class="my-3 h-px bg-zinc-200"></div>

                @if($category)
                    <button wire:click="$set('category', '')"
                            class="flex w-full items-center gap-2 rounded-lg border border-zinc-200 px-3 py-2 text-left text-xs text-zinc-500 transition hover:bg-zinc-50">
                        Hapus Filter
                    </button>
                @endif
            </nav>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1">
        <header class="mb-6">
            <h1 class="text-3xl font-bold leading-tight text-zinc-900 lg:text-[40px] lg:leading-[48px]">Katalog Produk</h1>

            {{-- Category Pills (mobile + desktop) --}}
            <div class="mt-4 flex flex-wrap gap-2">
                <button wire:click="$set('category', '')"
                        class="rounded-full border px-4 py-1.5 text-sm font-medium transition
                            {{ $category === '' ? 'border-zinc-900 bg-zinc-900 text-white' : 'border-zinc-200 text-zinc-500 hover:bg-zinc-50' }}">
                    Semua
                </button>
                <button wire:click="$set('category', 'CUSTOM_SERVICE')"
                        class="rounded-full border px-4 py-1.5 text-sm font-medium transition
                            {{ $category === 'CUSTOM_SERVICE' ? 'border-zinc-900 bg-zinc-900 text-white' : 'border-zinc-200 text-zinc-500 hover:bg-zinc-50' }}">
                    Jasa Cetak
                </button>
                <button wire:click="$set('category', 'PHYSICAL_PRODUCT')"
                        class="rounded-full border px-4 py-1.5 text-sm font-medium transition
                            {{ $category === 'PHYSICAL_PRODUCT' ? 'border-zinc-900 bg-zinc-900 text-white' : 'border-zinc-200 text-zinc-500 hover:bg-zinc-50' }}">
                    Kertas & ATK
                </button>
            </div>

            {{-- Search --}}
            <div class="relative mt-4 sm:max-w-xs">
                <flux:icon.magnifying-glass class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400" />
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       placeholder="Cari katalog..."
                       class="w-full rounded-lg border border-zinc-200 bg-white py-2 pl-9 pr-3 text-sm text-zinc-900 placeholder-zinc-400 outline-none transition focus:border-zinc-400 focus:ring-1 focus:ring-zinc-400" />
            </div>
        </header>

        {{-- Product Grid --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($this->products as $product)
                <a href="{{ route('product.detail', $product->slug) }}" wire:navigate
                   class="group flex flex-col overflow-hidden rounded-lg border border-zinc-200 bg-white transition hover:shadow-sm">

                    {{-- Image --}}
                    <div class="relative h-48 overflow-hidden bg-zinc-100">
                        @if($product->getFirstMediaUrl('images', 'thumb'))
                            <img src="{{ $product->getFirstMediaUrl('images', 'thumb') }}" alt="{{ $product->name }}"
                                 class="h-full w-full object-cover transition group-hover:scale-105" loading="lazy" />
                        @else
                            <div class="flex h-full w-full items-center justify-center">
                                <flux:icon.photo class="h-10 w-10 text-zinc-300" />
                            </div>
                        @endif
                        <span class="absolute right-2 top-2 rounded px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider
                            {{ $product->is_custom_dimension
                                ? 'bg-amber-500 text-white'
                                : 'bg-emerald-500 text-white' }}">
                            {{ $product->is_custom_dimension ? 'Custom Ukuran' : 'Ready Stock' }}
                        </span>
                    </div>

                    {{-- Body --}}
                    <div class="flex flex-1 flex-col p-4">
                        <h3 class="font-bold text-zinc-900">{{ $product->name }}</h3>
                        <p class="mt-1 line-clamp-2 text-sm text-zinc-500">{{ $product->description }}</p>

                        @if($product->is_custom_dimension)
                            <div class="mt-auto pt-4">
                                <span class="inline-flex w-full items-center justify-center gap-1 rounded-lg bg-zinc-900 py-2 text-sm font-medium text-white transition group-hover:bg-zinc-700">
                                    <flux:icon.calculator class="h-4 w-4" />
                                    Kalkulator Dimensi
                                </span>
                            </div>
                        @else
                            <div class="mt-auto flex items-center justify-between pt-4">
                                <span class="font-mono text-sm font-bold text-zinc-900">
                                    {{ $product->base_price_formatted }}
                                    <span class="text-xs font-normal text-zinc-400">/{{ $product->unit_label }}</span>
                                </span>
                            </div>
                            <span class="mt-2 inline-flex w-full items-center justify-center gap-1 rounded-lg border border-zinc-200 py-2 text-sm font-medium text-zinc-900 transition group-hover:border-zinc-400">
                                <flux:icon.shopping-cart class="h-4 w-4" />
                                + Keranjang
                            </span>
                        @endif
                    </div>
                </a>
            @empty
                <div class="col-span-full py-16 text-center">
                    <h3 class="font-bold text-zinc-900">Produk tidak ditemukan</h3>
                    <p class="mt-1 text-sm text-zinc-500">Ubah kata kunci atau kategori pencarian.</p>
                </div>
            @endforelse
        </div>
    </main>
</div>