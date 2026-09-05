<?php

namespace App\Livewire\Frontend;

use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.frontend')]
class ProductCatalog extends Component
{
    #[Url]
    public string $category = '';

    #[Url]
    public string $search = '';

    public function render()
    {
        return view('livewire.frontend.product-catalog');
    }

    #[Computed]
    public function products()
    {
        return Product::query()
            ->when($this->category, fn ($q) => $q->where('category', $this->category))
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->orderBy('category')
            ->orderBy('name')
            ->get();
    }
}
