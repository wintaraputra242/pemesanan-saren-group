@php
    $minOrder = $this->minOrder;
    $pricing = $this->pricing;
    $unitPrice = number_format($pricing['unit_price'], 0, ',', '.'); 
    $subtotal = number_format($pricing['subtotal'], 0, ',', '.');
@endphp

<div class="grid gap-8 lg:grid-cols-2">
    <div>
        @if($product->getFirstMediaUrl('images', 'thumb'))
            <img src="{{ $product->getFirstMediaUrl('images', 'thumb') }}" alt="{{ $product->name }}" class="mb-4 w-full rounded-xl border border-zinc-200 object-cover" />
        @endif
        <flux:heading size="xl">{{ $product->name }}</flux:heading>
        <flux:text class="mt-1">{{ $product->description }}</flux:text>

        <div class="mt-6 space-y-4">
            <flux:field label="Jumlah ({{ $product->unit_label }})">
                <flux:input type="number" min="{{ $minOrder }}" wire:model.live="quantity" />
                <flux:error name="quantity" />
            </flux:field>
            <flux:text class="text-xs text-zinc-500">Minimum order: {{ $minOrder }} {{ $product->unit_label }}</flux:text>

            @if($product->variants->isNotEmpty())
                <flux:field label="Pilihan">
                    <flux:select wire:model.live="selectedVariant">
                        @foreach($product->variants as $variant)
                            <option value="{{ $variant->name }}" @selected($variant->name === $selectedVariant)>{{ $variant->name }}</option>
                        @endforeach
                    </flux:select>
                </flux:field>
            @endif

            @if($product->slug === 'kartu-undangan')
                <flux:field label="Detail Acara (nama, waktu, tempat)">
                    <flux:textarea wire:model.live="orderDetails" rows="4" placeholder="Contoh: Pernikahan I Wayan & Ni Made, 10 Nov 2026, Banjar Sasih..." />
                </flux:field>
            @endif

            @if($product->requires_design_file)
                <flux:field label="File Desain (PDF, TIFF, PSD, CDR, AI, PNG)">
                    <flux:input type="file" wire:model="artworkFile" />
                    <flux:error name="artworkFile" />
                </flux:field>
            @endif

            <flux:button variant="primary" class="w-full" wire:click="addToCart">
                Tambah ke Keranjang
            </flux:button>
        </div>
    </div>

    <div>
        @if($product->getFirstMediaUrl('images', 'thumb'))
            <img src="{{ $product->getFirstMediaUrl('images', 'thumb') }}" alt="{{ $product->name }}" class="mb-4 w-full rounded-xl border border-zinc-200 object-cover" />
        @endif
        <flux:card class="space-y-3">
            <flux:heading size="lg">Estimasi Harga</flux:heading>
            <div class="flex justify-between">
                <flux:text>Harga satuan</flux:text>
                <flux:heading>Rp {{ $unitPrice }}</flux:heading>
            </div>
            <div class="flex justify-between">
                <flux:text>Subtotal (x{{ $quantity }})</flux:text>
                <flux:heading class="font-bold text-indigo-600">Rp {{ $subtotal }}</flux:heading>
            </div>
        </flux:card>
    </div>
</div>
