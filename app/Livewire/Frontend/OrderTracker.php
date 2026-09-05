<?php

namespace App\Livewire\Frontend;

use App\Enums\OrderStatus;
use App\Models\Order;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.frontend')]
class OrderTracker extends Component
{
    public Order $order;

    public function mount(string $invoice): void
    {
        $this->order = Order::with(['items.product'])
            ->where('invoice_number', $invoice)
            ->firstOrFail();
    }

    #[Computed]
    public function timeline(): array
    {
        $steps = array_values(OrderStatus::cases());
        $currentIndex = array_search($this->order->status, $steps, true);
        $currentIndex = $currentIndex === false ? 0 : $currentIndex;

        $result = [];
        foreach ($steps as $i => $step) {
            if ($this->order->status === OrderStatus::CANCELLED && $i >= $currentIndex && $i < count($steps) - 1) {
                continue;
            }
            $result[] = [
                'label' => $step->getLabel(),
                'achieved' => $this->order->status === OrderStatus::CANCELLED
                    ? ($i === $currentIndex)
                    : ($i <= $currentIndex),
            ];
        }

        return $result;
    }

    public function render()
    {
        return view('livewire.frontend.order-tracker');
    }
}
