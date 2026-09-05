<?php

namespace App\Livewire\Frontend;

use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.frontend')]
class ProductDetail extends Component
{
    public Product $product;

    public function mount(string $slug): void
    {
        $this->product = Product::with('variants')->where('slug', $slug)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.frontend.product-detail');
    }

    #[Computed]
    public function calculatorComponent(): string
    {
        if (! $this->product->is_custom_dimension) {
            return 'frontend.standard-custom-order';
        }

        return $this->product->slug === 'stiker-custom'
            ? 'frontend.sticker-calculator'
            : 'frontend.banner-calculator';
    }
}
