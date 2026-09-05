<div>
    <div class="mb-6">
        <flux:button variant="ghost" href="{{ route('catalog') }}" wire:navigate icon="arrow-left">
            Kembali ke Katalog
        </flux:button>
    </div>

    @if($product->is_custom_dimension && $product->slug === 'stiker-custom')
        <livewire:frontend.sticker-calculator :product="$product" />
    @elseif($product->is_custom_dimension)
        <livewire:frontend.banner-calculator :product="$product" />
    @else
        <livewire:frontend.standard-custom-order :product="$product" />
    @endif
</div>
