<?php

namespace App\Livewire\Frontend;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.frontend')]
class CartDrawer extends Component
{
    #[Computed]
    public function cartItems(): array
    {
        return session('cart', []);
    }

    #[Computed]
    public function cartCount(): int
    {
        return count($this->cartItems);
    }

    #[Computed]
    public function cartTotal(): int
    {
        return array_sum(array_column($this->cartItems, 'subtotal'));
    }

    public function removeFromCart(int $index): void
    {
        $cart = $this->cartItems;
        unset($cart[$index]);
        session(['cart' => array_values($cart)]);
        $this->dispatch('cart-updated');
    }

    public function clearCart(): void
    {
        session()->forget('cart');
        $this->dispatch('cart-updated');
    }

    public function render(): View
    {
        return view('livewire.frontend.cart-drawer');
    }
}
