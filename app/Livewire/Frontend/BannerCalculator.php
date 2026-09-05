<?php

namespace App\Livewire\Frontend;

use App\Models\Product;
use App\Services\PricingCalculatorService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class BannerCalculator extends Component
{
    use WithFileUploads;

    public Product $product;

    public float $widthCm = 100;

    public float $heightCm = 100;

    public int $quantity = 1;

    public ?string $selectedVariant = '';

    public string $finishing = 'Mata Ayam 4 Sudut';

    #[Validate('nullable|file|max:51200|mimes:pdf,tiff,tif,jpg,jpeg,png,zip,rar,cdr,psd,ai')]
    public $artworkFile;

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

        return app(PricingCalculatorService::class)->calculateCustomDimension(
            widthCm: (float) $this->widthCm,
            heightCm: (float) $this->heightCm,
            basePricePerM2: $effectiveBase,
            quantity: (int) $this->quantity,
            minAreaM2: (float) ($this->product->min_size_m2 ?? 0.25),
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
            'finishing_note' => $this->finishing,
            'design_file_path' => $path,
        ]);

        $this->dispatch('cart-updated');
        $this->reset('artworkFile');
    }

    public function render()
    {
        return view('livewire.frontend.banner-calculator');
    }
}
