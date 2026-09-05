<?php

use App\Enums\DeliveryMethod;
use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Pages\ViewOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $this->actingAs($user);
});

it('lists orders as an admin', function () {
    $order = Order::factory()->create([
        'invoice_number' => 'SRN-20260830-0101',
        'customer_phone' => '081234567890',
    ]);

    Livewire::test(ListOrders::class)
        ->assertCanSeeTableRecords([$order]);
});

it('renders the order view page with infolist', function () {
    $order = Order::factory()->create([
        'invoice_number' => 'SRN-20260830-0102',
        'customer_name' => 'Dewa',
    ]);

    Livewire::test(ViewOrder::class, [
        'record' => $order->getKey(),
    ])->assertOk();
});

it('creates an order with items and recalculated total', function () {
    $product = Product::factory()->create(['base_price' => 25000]);

    Livewire::test(CreateOrder::class)
        ->fillForm([
            'invoice_number' => 'SRN-20260830-0200',
            'customer_name' => 'Wayan Uji',
            'customer_phone' => '08123998877',
            'delivery_method' => DeliveryMethod::PICKUP->value,
            'status' => OrderStatus::PENDING_PAYMENT->value,
            'items' => [
                [
                    'product_id' => $product->getKey(),
                    'quantity' => 10,
                    'unit_price' => 25000,
                ],
            ],
            'total_amount' => 250000,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $order = Order::where('invoice_number', 'SRN-20260830-0200')->first();
    expect($order)->not->toBeNull()
        ->and($order->customer_name)->toBe('Wayan Uji')
        ->and($order->items()->count())->toBe(1)
        ->and($order->total_amount)->toBe(250000);
});
