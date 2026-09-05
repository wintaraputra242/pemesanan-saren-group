<?php

use App\Models\Order;
use App\Models\Product;

it('shows order details and status timeline for a valid invoice', function () {
    $product = Product::factory()->create(['name' => 'Banner Produksi']);
    $order = Order::factory()->create([
        'invoice_number' => 'SRN-20260830-0050',
        'customer_name' => 'Komang',
        'status' => 'IN_PRODUCTION',
    ]);
    $order->items()->create([
        'product_id' => $product->id,
        'quantity' => 2,
        'unit_price' => 25000,
        'subtotal' => 50000,
    ]);

    $this->get(route('order.track', 'SRN-20260830-0050'))
        ->assertOk()
        ->assertSee('Banner Produksi')
        ->assertSee('Komang')
        ->assertSee('Sedang Dicetak');
});

it('returns 404 for an invalid invoice', function () {
    $this->get(route('order.track', 'INVALID-INVOICE'))
        ->assertNotFound();
});
