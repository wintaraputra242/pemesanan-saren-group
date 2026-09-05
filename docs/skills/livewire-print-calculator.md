---
name: livewire-print-calculator
description: Domain rules and reactive Livewire 3 implementation patterns for dynamic area printing ($m^2$), minimum chargeable boundaries, and finishing calculations.
---

# Livewire 3 Printing Calculator Skill

## 1. Core Dimension & Area Math
Perhitungan harga cetakan berbasis luas meter persegi ($m^2$) wajib memperhatikan batas minimum penagihan (*minimum chargeable area*):

```php
namespace App\Services;

class PricingCalculatorService
{
    /**
     * Hitung subtotal untuk produk berdimensi (Banner, Stiker, dsb).
     */
    public function calculateCustomDimension(
        float $widthCm,
        float $heightCm,
        int $basePricePerM2,
        int $quantity = 1,
        float $minAreaM2 = 0.25,
        int $finishingPricePerUnit = 0
    ): array {
        // Konversi cm2 ke m2
        $rawAreaM2 = ($widthCm * $heightCm) / 10000;
        
        // Terapkan batas minimum luas (default 0.25 m2 / 50cm x 50cm)
        $billableAreaM2 = max($minAreaM2, $rawAreaM2);
        
        // Hitung harga satuan dan total
        $unitPrice = (int) round(($billableAreaM2 * $basePricePerM2) + $finishingPricePerUnit);
        $subtotal = $unitPrice * $quantity;

        return [
            'raw_area_m2' => round($rawAreaM2, 2),
            'billable_area_m2' => round($billableAreaM2, 2),
            'unit_price' => $unitPrice,
            'subtotal' => $subtotal,
        ];
    }
}
```

## 2. Livewire 3 Reactive Component Pattern
Gunakan `#[Computed]` attribute untuk perhitungan live yang terisolasi dan efisien:

```php
namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Services\PricingCalculatorService;

class BannerCalculator extends Component
{
    use WithFileUploads;

    public Product $product;
    
    public float $widthCm = 100;
    public float $heightCm = 100;
    public int $quantity = 1;
    public ?string $selectedVariant = 'Flex China';
    public string $finishing = 'Mata Ayam 4 Sudut';
    
    #[Validate('nullable|file|max:51200|mimes:pdf,tiff,tif,jpg,jpeg,png,zip,rar,cdr,psd,ai')]
    public $artworkFile;

    #[Computed]
    public function pricing(): array
    {
        $variantDiff = $this->product->variants->firstWhere('name', $this->selectedVariant)?->price_diff ?? 0;
        $effectiveBasePrice = $this->product->base_price + $variantDiff;

        return app(PricingCalculatorService::class)->calculateCustomDimension(
            widthCm: (float) $this->widthCm,
            heightCm: (float) $this->heightCm,
            basePricePerM2: $effectiveBasePrice,
            quantity: (int) $this->quantity,
            minAreaM2: (float) ($this->product->min_size_m2 ?? 0.25)
        );
    }
}
```
