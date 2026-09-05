<?php

namespace App\Livewire\Frontend;

use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class StandardCustomOrder extends Component
{
    use WithFileUploads;

    public Product $product;

    public int $quantity = 1;

    public ?string $selectedVariant = '';

    public ?string $orderDetails = null;

    #[Validate('nullable|file|max:51200|mimes:pdf,tiff,tif,jpg,jpeg,png,zip,rar,cdr,psd,ai')]
    public $artworkFile;

    private const MIN_ORDER = [
        'kartu-undangan' => 50,
        'payung-sablon' => 12,
    ];

    public function mount(Product $product): void
    {
        $this->product = $product;
        $this->selectedVariant = $product->variants->first()?->name ?? '';
    }

    #[Computed]
    public function pricing(): array
    {
        $variantDiff = $this->product->variants->firstWhere('name', $this->selectedVariant)?->price_diff ?? 0;
        $unitPrice = $this->product->base_price + $variantDiff;

        return [
            'unit_price' => $unitPrice,
            'subtotal' => $unitPrice * $this->quantity,
        ];
    }

    #[Computed]
    public function minOrder(): int
    {
        return self::MIN_ORDER[$this->product->slug] ?? 1;
    }

    public function addToCart(): void
    {
        $minimum = $this->minOrder;

        $this->validate([
            'quantity' => ['integer', 'min:'.$minimum],
        ]);

        $path = $this->artworkFile
            ? $this->artworkFile->store('artworks/tmp/'.date('Y/m'), 'public')
            : null;

        $pricing = $this->pricing;

        session()->push('cart', [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'variant_name' => $this->selectedVariant ?: null,
            'width_cm' => null,
            'height_cm' => null,
            'calculated_area' => null,
            'quantity' => $this->quantity,
            'unit_price' => $pricing['unit_price'],
            'subtotal' => $pricing['subtotal'],
            'finishing_note' => $this->product->slug === 'kartu-undangan' ? ($this->orderDetails ?: null) : null,
            'design_file_path' => $path,
        ]);

        $this->dispatch('cart-updated');
        $this->reset('artworkFile');
    }

    public function render()
    {
        return view('livewire.frontend.standard-custom-order');
    }
}
