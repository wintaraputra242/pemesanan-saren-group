<?php

namespace Database\Factories;

use App\Enums\DeliveryMethod;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'invoice_number' => 'SRN-'.fake()->unique()->numerify('########-####'),
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->numerify('08##########'),
            'customer_email' => fake()->safeEmail(),
            'delivery_method' => DeliveryMethod::PICKUP->value,
            'delivery_address' => null,
            'total_amount' => fake()->numberBetween(10000, 5000000),
            'status' => OrderStatus::PENDING_PAYMENT->value,
            'notes' => null,
        ];
    }
}
