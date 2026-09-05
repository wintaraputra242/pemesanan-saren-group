<?php

namespace App\Livewire\Frontend;

use App\Models\Product;
use App\Services\PricingCalculatorService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class StickerCalculator extends Component
{
    use WithFileUploads;

    public Product $product;

    public float $widthCm = 50;

    public float $heightCm = 50;

    public int $quantity = 1;

    public ?string $selectedVariant = '';

    public string $cuttingMethod = 'lembaran';

    #[Validate('nullable|file|max:51200|mimes:pdf,tiff,tif,jpg,jpeg,png,zip,rar,cdr,psd,ai')]
    public $artworkFile;

    private const CUTTING_PRICE = [
        'die_cut' => 5000,
        'kiss_cut' => 3000,
        'lembaran' => 0,
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
        $effectiveBase = $this->product->base_price + $variantDiff;
        $cutting = self::CUTTING_PRICE[$this->cuttingMethod] ?? 0;

        return app(PricingCalculatorService::class)->calculateCustomDimension(
            widthCm: (float) $this->widthCm,
            heightCm: (float) $this->heightCm,
            basePricePerM2: $effectiveBase,
            quantity: (int) $this->quantity,
            minAreaM2: (float) ($this->product->min_size_m2 ?? 0.25),
            finishingPricePerM2: $cutting,
        );
    }

    public function addToCart(): void
    {
        $this->validate();

        $path = $this->artworkFile
            ? $this->artworkFile->store('artworks/tmp/'.date('Y/m'), 'public')
            : null;

        $pricing = $this->pricing;

        session()->push('cart', [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'variant_name' => $this->selectedVariant,
            'width_cm' => $this->widthCm,
            'height_cm' => $this->heightCm,
            'calculated_area' => $pricing['billable_area_m2'],
            'quantity' => $this->quantity,
            'unit_price' => $pricing['unit_price'],
            'subtotal' => $pricing['subtotal'],
            'finishing_note' => 'Cutting: '.ucfirst(str_replace('_', ' ', $this->cuttingMethod)),
            'design_file_path' => $path,
        ]);

        $this->dispatch('cart-updated');
        $this->reset('artworkFile');
    }

    public function render()
    {
        return view('livewire.frontend.sticker-calculator');
    }
}
