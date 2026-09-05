@php
    $waNumber = '6287860042888';
    $waLink = 'https://wa.me/' . $waNumber;
    $featured = \App\Models\Product::query()
        ->with('media')
        ->where('category', \App\Enums\ProductCategory::CUSTOM_SERVICE)
        ->orderBy('name')
        ->take(3)
        ->get()
        ->merge(
            \App\Models\Product::query()
                ->with('media')
                ->where('category', \App\Enums\ProductCategory::PHYSICAL_PRODUCT)
                ->orderBy('base_price')
                ->take(3)
                ->get()
        )
        ->take(6);
@endphp

<div class="flex flex-col gap-0">

    {{-- Hero Section --}}
    <section class="flex flex-col gap-8 py-12 md:flex-row md:items-center md:gap-12 lg:py-16">
        <div class="flex-1 space-y-6">
            <h1 class="text-3xl font-bold leading-tight tracking-tight text-zinc-900 sm:text-4xl lg:text-[40px] lg:leading-[48px]">
                Percetakan Digital Cepat, Presisi & Berkualitas di Bali
            </h1>
            <p class="max-w-lg text-lg leading-relaxed text-zinc-500">
                Spanduk, stiker, kartu nama, ATK. Solusi cetak profesional untuk kebutuhan bisnis Anda dengan hasil presisi tinggi.
            </p>
            <div class="flex flex-wrap gap-3 pt-2">
                <a href="{{ route('catalog') }}" wire:navigate
                   class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-6 py-3 text-sm font-bold text-white transition hover:bg-zinc-800">
                    Pesan Cetak Custom
                    <flux:icon.arrow-right class="h-4 w-4" />
                </a>
                <a href="{{ route('catalog') }}" wire:navigate
                   class="inline-flex items-center gap-2 rounded-lg border-2 border-zinc-300 px-6 py-3 text-sm font-bold text-zinc-900 transition hover:bg-zinc-50">
                    <flux:icon.archive-box class="h-4 w-4" />
                    Lihat Katalog ATK
                </a>
            </div>
        </div>

        <div class="flex-1">
            <div class="relative aspect-[4/3] w-full overflow-hidden rounded-xl border border-zinc-200 bg-zinc-100">
                {{-- Hero image: produk pertama dengan media, atau placeholder --}}
                @php $heroProduct = $featured->first(fn ($p) => $p->getFirstMediaUrl('images')); @endphp
                @if($heroProduct)
                    <img src="{{ $heroProduct->getFirstMediaUrl('images') }}"
                         alt="Workshop CV. Saren Grup"
                         class="absolute inset-0 h-full w-full object-cover" />
                @else
                    <div class="flex h-full w-full items-center justify-center">
                        <div class="text-center text-zinc-400">
                            <flux:icon.printer class="mx-auto h-16 w-16" />
                            <p class="mt-2 text-sm">Percetakan Digital Modern</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Trust Strip --}}
    <section class="-mx-4 border-y border-zinc-200 bg-zinc-50 px-4 py-5 sm:-mx-4">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-center gap-8 md:justify-between md:gap-4">
            <div class="flex items-center gap-3">
                <flux:icon.cog-6-tooth class="h-7 w-7 text-zinc-900" />
                <div>
                    <p class="text-sm font-bold text-zinc-900">3+ Mesin Modern</p>
                    <p class="text-xs text-zinc-500">Teknologi Cetak Terkini</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <flux:icon.user-group class="h-7 w-7 text-zinc-900" />
                <div>
                    <p class="text-sm font-bold text-zinc-900">12+ Tenaga Ahli</p>
                    <p class="text-xs text-zinc-500">Profesional Berpengalaman</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <flux:icon.clock class="h-7 w-7 text-zinc-900" />
                <div>
                    <p class="text-sm font-bold text-zinc-900">Sejak 2009</p>
                    <p class="text-xs text-zinc-500">Dipercaya di Bali</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Produk Unggulan --}}
    <section class="py-12">
        <div class="mb-8 flex items-end justify-between">
            <div>
                <h2 class="text-2xl font-bold text-zinc-900">Produk Unggulan</h2>
                <p class="mt-1 text-sm text-zinc-500">Paling sering dipesan pelanggan kami.</p>
            </div>
            <a href="{{ route('catalog') }}" wire:navigate class="text-sm font-medium text-zinc-500 transition hover:text-zinc-900">
                Lihat semua &rarr;
            </a>
        </div>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($featured as $product)
                <a href="{{ route('product.detail', $product->slug) }}" wire:navigate
                   class="group flex flex-col overflow-hidden rounded-xl border border-zinc-200 bg-white transition hover:-translate-y-1 hover:shadow-md">
                    <div class="aspect-[4/3] w-full overflow-hidden bg-zinc-100">
                        @if($product->getFirstMediaUrl('images', 'thumb'))
                            <img src="{{ $product->getFirstMediaUrl('images', 'thumb') }}" alt="{{ $product->name }}"
                                 class="h-full w-full object-cover transition group-hover:scale-105" loading="lazy" />
                        @else
                            <div class="flex h-full w-full items-center justify-center">
                                <flux:icon.photo class="h-10 w-10 text-zinc-300" />
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-1 flex-col p-4">
                        <span class="mb-2 self-start rounded-full px-2.5 py-0.5 text-xs font-medium
                            {{ $product->category === \App\Enums\ProductCategory::CUSTOM_SERVICE
                                ? 'bg-indigo-50 text-indigo-700'
                                : 'bg-zinc-100 text-zinc-600' }}">
                            {{ $product->category->getLabel() }}
                        </span>
                        <h3 class="font-bold text-zinc-900">{{ $product->name }}</h3>
                        <p class="mt-1 line-clamp-2 text-sm text-zinc-500">{{ $product->description }}</p>
                        <div class="mt-auto flex items-end justify-between pt-4">
                            <div>
                                <p class="text-xs text-zinc-400">Mulai dari</p>
                                <p class="text-lg font-bold text-zinc-900">{{ $product->base_price_formatted }}</p>
                            </div>
                            <span class="rounded-lg bg-zinc-900 px-3 py-1.5 text-xs font-bold text-white transition group-hover:bg-zinc-700">
                                Pesan
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <p class="col-span-full text-sm text-zinc-500">Katalog segera hadir.</p>
            @endforelse
        </div>
    </section>

    {{-- Cara Pemesanan --}}
    <section class="py-12">
        <h2 class="mb-8 text-center text-2xl font-bold text-zinc-900">Cara Pemesanan</h2>
        <div class="mx-auto grid max-w-4xl grid-cols-1 gap-5 sm:grid-cols-3">
            @foreach([
                ['icon' => 'calculator', 'title' => 'Pilih & Hitung', 'desc' => 'Pilih produk, masukkan dimensi, harga terhitung otomatis.'],
                ['icon' => 'cloud-arrow-up', 'title' => 'Upload & Checkout', 'desc' => 'Kirim file desain, checkout, konfirmasi via WhatsApp.'],
                ['icon' => 'truck', 'title' => 'Lacak & Ambil', 'desc' => 'Pantau status produksi sampai siap diambil atau dikirim.'],
            ] as $i => $step)
                <div class="flex flex-col items-start rounded-xl border border-zinc-200 bg-white p-6">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-zinc-900 text-sm font-bold text-white">{{ $i + 1 }}</span>
                    <h3 class="mt-4 font-bold text-zinc-900">{{ $step['title'] }}</h3>
                    <p class="mt-1 text-sm text-zinc-500">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Track Pesanan --}}
    <section id="track" class="py-12">
        <div class="mx-auto max-w-xl rounded-xl border border-zinc-200 bg-white p-8 text-center">
            <h2 class="text-xl font-bold text-zinc-900">Lacak Pesanan</h2>
            <p class="mt-1 text-sm text-zinc-500">Masukkan nomor invoice untuk melihat status produksi.</p>
            <div class="mt-5">
                <flux:input wire:model="trackInvoice" placeholder="Contoh: SRN-20260830-0001" icon="magnifying-glass" />
            </div>
            <button wire:click="goTrack"
                    class="mt-4 inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-6 py-2.5 text-sm font-bold text-white transition hover:bg-zinc-800">
                <flux:icon.magnifying-glass class="h-4 w-4" />
                Lacak
            </button>
            <flux:error name="trackInvoice" />
        </div>
    </section>

    {{-- Profil & Kontak --}}
    <section class="grid gap-6 py-12 lg:grid-cols-2">
        <div class="space-y-4 rounded-xl border border-zinc-200 bg-white p-6">
            <h2 class="text-xl font-bold text-zinc-900">Tentang CV. Saren Grup</h2>
            <p class="text-sm leading-relaxed text-zinc-500">
                Bermula tahun 2009 sebagai warung internet Saren Komputer di Sibang Kaja, Bali, lalu resmi
                berbadan hukum pada 2015 sebagai CV. Saren Grup — sentra digital printing & percetakan modern.
            </p>
            <a href="{{ $waLink }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-zinc-500 transition hover:text-zinc-900">
                <flux:icon.map-pin class="h-4 w-4" />
                Jalan Raya Rijasa No. 6, Sibang Kaja, Bali
            </a>
        </div>
        <div class="space-y-3 rounded-xl border border-zinc-200 bg-white p-6">
            <h2 class="text-xl font-bold text-zinc-900">Hubungi Kami</h2>
            <p class="text-sm text-zinc-500">
                <span class="font-semibold text-zinc-700">WhatsApp:</span>
                <a href="{{ $waLink }}" class="text-indigo-600 hover:underline" target="_blank">+62 878-6004-2888</a>
            </p>
            <p class="text-sm text-zinc-500">
                <span class="font-semibold text-zinc-700">Email:</span>
                <a href="mailto:sarengrup@gmail.com" class="text-indigo-600 hover:underline">sarengrup@gmail.com</a>
            </p>
            <p class="text-sm text-zinc-500">
                <span class="font-semibold text-zinc-700">Instagram:</span> @cvsaren_grup
            </p>
            <p class="text-xs text-zinc-400">Buka: Senin – Sabtu, 08.00 – 18.00 WITA</p>
        </div>
    </section>
</div>