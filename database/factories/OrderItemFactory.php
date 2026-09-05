<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        $unitPrice = fake()->numberBetween(5000, 100000);

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'variant_name' => null,
            'width_cm' => null,
            'height_cm' => null,
            'calculated_area' => null,
            'quantity' => fake()->numberBetween(1, 10),
            'unit_price' => $unitPrice,
            'subtotal' => $unitPrice,
            'design_file_path' => null,
            'finishing_note' => null,
        ];
    }
}
