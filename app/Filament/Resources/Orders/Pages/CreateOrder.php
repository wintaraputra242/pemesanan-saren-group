<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Enums\DeliveryMethod;
use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['delivery_method'] ??= DeliveryMethod::PICKUP->value;
        $data['status'] ??= OrderStatus::PENDING_PAYMENT->value;

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var Order $record */
        $record = $this->getRecord();

        $record->updateQuietly([
            'total_amount' => (int) $record->items()->sum('subtotal'),
        ]);
    }
}
