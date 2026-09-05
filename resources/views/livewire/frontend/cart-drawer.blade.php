<div>
    <flux:dropdown position="bottom" align="end">
        <flux:button variant="ghost" icon="shopping-cart">
            Keranjang
            @if($this->cartCount > 0)
                <flux:badge color="indigo" inset="top right">{{ $this->cartCount }}</flux:badge>
            @endif
        </flux:button>

        <flux:menu class="w-80 max-w-[calc(100vw-2rem)]">
            @forelse($this->cartItems as $i => $item)
                <div class="flex items-start justify-between gap-3 px-2 py-2">
                    <div class="min-w-0 text-sm">
                        <div class="font-medium text-zinc-900">{{ $item['product_name'] }}</div>
                        <div class="text-xs text-zinc-500">
                            @if(($item['variant_name'] ?? null)){{ $item['variant_name'] }} · @endif
                            x{{ $item['quantity'] }} · Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </div>
                        @if(($item['width_cm'] ?? null))
                            <div class="text-xs text-zinc-500">{{ $item['width_cm'] }} × {{ $item['height_cm'] }} cm</div>
                        @endif
                    </div>
                    <button type="button" wire:click="removeFromCart({{ $i }})" class="text-zinc-400 hover:text-red-500">
                        <flux:icon name="x-mark" variant="micro" />
                    </button>
                </div>
            @empty
                <div class="px-4 py-3 text-sm text-zinc-500">Keranjang kosong.</div>
            @endforelse

            @if($this->cartCount > 0)
                <flux:separator />
                <div class="flex items-center justify-between px-2 py-2">
                    <span class="text-sm font-medium text-zinc-900">Total</span>
                    <span class="text-sm font-bold text-indigo-600">Rp {{ number_format($this->cartTotal, 0, ',', '.') }}</span>
                </div>
                <div class="px-2 pb-2">
                    <flux:button variant="primary" class="w-full" href="{{ route('checkout') }}" wire:navigate>
                        Checkout
                    </flux:button>
                </div>
            @endif
        </flux:menu>
    </flux:dropdown>
</div>
