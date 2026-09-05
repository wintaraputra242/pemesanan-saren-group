<?php

use App\Services\PricingCalculatorService;

it('charges minimum area for banner below 0.25 m2', function () {
    $result = app(PricingCalculatorService::class)->calculateCustomDimension(30, 40, 25000);

    expect($result['raw_area_m2'])->toBe(0.12)
        ->and($result['billable_area_m2'])->toBe(0.25)
        ->and($result['unit_price'])->toBe(6250)
        ->and($result['subtotal'])->toBe(6250);
});

it('prices 1 m2 flex china banner at 25000', function () {
    $result = app(PricingCalculatorService::class)->calculateCustomDimension(100, 100, 25000);

    expect($result['unit_price'])->toBe(25000)
        ->and($result['subtotal'])->toBe(25000);
});

it('adds flex korea variant premium to base price', function () {
    $result = app(PricingCalculatorService::class)->calculateCustomDimension(100, 100, 25000 + 15000);

    expect($result['unit_price'])->toBe(40000);
});

it('prices vinyl doff sticker with die cut finishing per m2', function () {
    $result = app(PricingCalculatorService::class)->calculateCustomDimension(
        50, 50, 35000 + 5000, 1, 0.25, 5000
    );

    expect($result['unit_price'])->toBe(11250);
});

it('multiplies by quantity for subtotal', function () {
    $result = app(PricingCalculatorService::class)->calculateCustomDimension(100, 100, 25000, 2);

    expect($result['unit_price'])->toBe(25000)
        ->and($result['subtotal'])->toBe(50000);
});
