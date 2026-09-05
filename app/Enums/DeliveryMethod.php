<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DeliveryMethod: string implements HasLabel
{
    case PICKUP = 'PICKUP';
    case COURIER = 'COURIER';

    public function getLabel(): string
    {
        return match ($this) {
            self::PICKUP => 'Ambil di Workshop',
            self::COURIER => 'Kirim via Kurir',
        };
    }
}
