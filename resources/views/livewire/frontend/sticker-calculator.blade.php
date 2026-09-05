<div class="grid gap-8 lg:grid-cols-2">
    <div>
        <flux:heading size="xl">{{ $product->name }}</flux:heading>
        <flux:text class="mt-1">{{ $product->description }}</flux:text>

        <div class="mt-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <flux:field label="Lebar (cm)">
                    <flux:input type="number" min="1" wire:model.live.debounce.250ms="widthCm" />
                </flux:field>
                <flux:field label="Tinggi (cm)">
                    <flux:input type="number" min="1" wire:model.live.debounce.250ms="heightCm" />
                </flux:field>
            </div>

            <flux:field label="Jumlah (pcs)">
                <flux:input type="number" min="1" wire:model.live="quantity" />
            </flux:field>

            @if($product->variants->isNotEmpty())
                <flux:field label="Pilihan Bahan">
                    <flux:select wire:model.live="selectedVariant">
                        @foreach($product->variants as $variant)
                            <option value="{{ $variant->name }}" @selected($variant->name === $selectedVariant)>{{ $variant->name }}</option>
                        @endforeach
                    </flux:select>
                </flux:field>
            @endif

            <flux:field label="Opsi Cutting">
                <flux:radio.group wire:model.live="cuttingMethod">
                    <flux:radio value="lembaran" label="Lembaran" />
                    <flux:radio value="kiss_cut" label="Kiss Cut" />
                    <flux:radio value="die_cut" label="Die Cut" />
                </flux:radio.group>
            </flux:field>

            @if($product->requires_design_file)
                <flux:field label="File Desain (PDF, TIFF, PSD, CDR, AI, ZIP)">
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
        <flux:card class="space-y-3">
            <flux:heading size="lg">Estimasi Harga</flux:heading>
            <div class="flex justify-between text-sm">
                <flux:text>Luas fisik</flux:text>
                <span>{{ $this->pricing['raw_area_m2'] }} m²</span>
            </div>
            <div class="flex justify-between text-sm">
                <flux:text>Luas terhitung</flux:text>
                <span class="font-semibold">{{ $this->pricing['billable_area_m2'] }} m²</span>
            </div>
            <flux:separator />
            <div class="flex justify-between">
                <flux:text>Harga satuan</flux:text>
                <flux:heading>Rp {{ number_format($this->pricing['unit_price'], 0, ',', '.') }}</flux:heading>
            </div>
            <div class="flex justify-between">
                <flux:text>Subtotal (x{{ $quantity }})</flux:text>
                <flux:heading class="font-bold text-indigo-600">Rp {{ number_format($this->pricing['subtotal'], 0, ',', '.') }}</flux:heading>
            </div>
            <flux:text class="text-xs">Harga cutting: Die Cut +Rp 5.000/m², Kiss Cut +Rp 3.000/m², Lembaran gratis.</flux:text>
        </flux:card>
    </div>
</div>
