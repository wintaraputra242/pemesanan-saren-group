<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\WhatsAppService;

it('generates order submission url with encoded payload', function () {
    $order = Order::factory()->create([
        'invoice_number' => 'SRN-20260830-0009',
        'customer_name' => 'Wayan',
        'customer_phone' => '081212121212',
    ]);
    $product = Product::factory()->create(['name' => 'Banner']);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'variant_name' => 'Flex China',
        'width_cm' => 100,
        'height_cm' => 100,
        'calculated_area' => 1.0,
        'quantity' => 1,
        'unit_price' => 25000,
        'subtotal' => 25000,
    ]);

    $url = app(WhatsAppService::class)->generateOrderSubmissionUrl($order);

    expect($url)->toBeString()
        ->and($url)->toContain('https://wa.me/6287860042888')
        ->and(rawurldecode($url))->toContain('SRN-20260830-0009')
        ->and(rawurldecode($url))->toContain('Wayan')
        ->and(rawurldecode($url))->toContain('Rp 25.000')
        ->and(rawurldecode($url))->toContain('Banner');
});

it('generates status update url to customer phone with tracking link', function () {
    $order = Order::factory()->create([
        'invoice_number' => 'SRN-20260830-0010',
        'customer_name' => 'Made',
        'customer_phone' => '081298765432',
        'status' => OrderStatus::READY_FOR_PICKUP->value,
    ]);

    $url = app(WhatsAppService::class)->generateCustomerUpdateUrl($order, 'Silakan diambil');

    expect($url)->toContain('https://wa.me/6281298765432')
        ->and(rawurldecode($url))->toContain('Siap Diambil')
        ->and(rawurldecode($url))->toContain('SRN-20260830-0010')
        ->and(rawurldecode($url))->toContain('/track/SRN-20260830-0010');
});

it('normalizes various phone formats', function () {
    $service = app(WhatsAppService::class);

    expect($service->normalizePhone('08123456789'))->toBe('628123456789')
        ->and($service->normalizePhone('+628123456789'))->toBe('628123456789')
        ->and($service->normalizePhone('0812-3456-789'))->toBe('628123456789');
});
