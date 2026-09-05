<?php

namespace App\Services;

class PricingCalculatorService
{
    /**
     * Hitung subtotal untuk produk berdimensi (Banner, Stiker, dsb).
     *
     * @return array{raw_area_m2: float, billable_area_m2: float, unit_price: int, subtotal: int}
     */
    public function calculateCustomDimension(
        float $widthCm,
        float $heightCm,
        int $basePricePerM2,
        int $quantity = 1,
        float $minAreaM2 = 0.25,
        int $finishingPricePerM2 = 0,
    ): array {
        $rawAreaM2 = ($widthCm * $heightCm) / 10000;
        $billableAreaM2 = max($minAreaM2, $rawAreaM2);
        $unitPrice = (int) round($billableAreaM2 * ($basePricePerM2 + $finishingPricePerM2));
        $subtotal = $unitPrice * $quantity;

        return [
            'raw_area_m2' => round($rawAreaM2, 2),
            'billable_area_m2' => round($billableAreaM2, 2),
            'unit_price' => $unitPrice,
            'subtotal' => $subtotal,
        ];
    }
}
