<?php

use App\Livewire\Frontend\CheckoutPage;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    Storage::fake('public');
});

it('creates order and redirects to whatsapp url on valid submission', function () {
    $product = Product::factory()->create([
        'name' => 'Banner',
        'base_price' => 25000,
    ]);

    session(['cart' => [
        [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'variant_name' => 'Flex China',
            'quantity' => 1,
            'unit_price' => 25000,
            'subtotal' => 25000,
            'width_cm' => 100,
            'height_cm' => 100,
            'calculated_area' => 1.0,
            'finishing_note' => null,
            'design_file_path' => null,
        ],
    ]]);

    Livewire::test(CheckoutPage::class)
        ->set('customerName', 'Wayan Suardana')
        ->set('customerPhone', '081234567890')
        ->set('deliveryMethod', 'PICKUP')
        ->call('submitOrder')
        ->assertHasNoErrors()
        ->assertRedirectContains('https://wa.me/6287860042888');

    $order = Order::first();
    expect($order)->not->toBeNull()
        ->and($order->customer_name)->toBe('Wayan Suardana')
        ->and($order->total_amount)->toBe(25000)
        ->and($order->status->value)->toBe('PENDING_PAYMENT')
        ->and($order->items)->toHaveCount(1)
        ->and(session('cart'))->toBeNull();
});

it('requires delivery address for courier delivery', function () {
    $product = Product::factory()->create();

    session(['cart' => [
        [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => 1,
            'unit_price' => 10000,
            'subtotal' => 10000,
        ],
    ]]);

    Livewire::test(CheckoutPage::class)
        ->set('customerName', 'Wayan')
        ->set('customerPhone', '081234567890')
        ->set('deliveryMethod', 'COURIER')
        ->call('submitOrder')
        ->assertHasErrors('deliveryAddress');

    expect(Order::count())->toBe(0);
});

it('persists uploaded artwork path from cart to order item', function () {
    $product = Product::factory()->create();

    Storage::disk('public')->put('artworks/2026/08/artwork.pdf', 'pdf-data');

    session(['cart' => [
        [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => 1,
            'unit_price' => 10000,
            'subtotal' => 10000,
            'design_file_path' => 'artworks/2026/08/artwork.pdf',
        ],
    ]]);

    Livewire::test(CheckoutPage::class)
        ->set('customerName', 'Wayan')
        ->set('customerPhone', '081234567890')
        ->set('deliveryMethod', 'PICKUP')
        ->call('submitOrder');

    $item = Order::first()->items->first();
    expect($item->design_file_path)->toBe('artworks/2026/08/artwork.pdf');
});

it('validates malformed phone number', function () {
    $product = Product::factory()->create();

    session(['cart' => [
        [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => 1,
            'unit_price' => 10000,
            'subtotal' => 10000,
        ],
    ]]);

    Livewire::test(CheckoutPage::class)
        ->set('customerName', 'Wayan')
        ->set('customerPhone', '123')
        ->set('deliveryMethod', 'PICKUP')
        ->call('submitOrder');

    expect(Order::count())->toBe(0);
});
