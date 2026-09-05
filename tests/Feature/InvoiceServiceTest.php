<?php

use App\Models\Order;
use App\Services\InvoiceService;

it('increments invoice counter within the same day', function () {
    $service = app(InvoiceService::class);

    $first = $service->generate();
    $second = $service->generate();

    expect($first)->toBe('SRN-'.date('Ymd').'-0001')
        ->and($second)->toBe('SRN-'.date('Ymd').'-0002');
});

it('continues from the highest existing invoice of the day', function () {
    Order::create([
        'invoice_number' => 'SRN-'.date('Ymd').'-0007',
        'customer_name' => 'Test',
        'customer_phone' => '081234567890',
        'delivery_method' => 'PICKUP',
        'total_amount' => 10000,
        'status' => 'PENDING_PAYMENT',
    ]);

    $next = app(InvoiceService::class)->generate();

    expect($next)->toBe('SRN-'.date('Ymd').'-0008');
});
