<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProductCategory: string implements HasLabel
{
    case CUSTOM_SERVICE = 'CUSTOM_SERVICE';
    case PHYSICAL_PRODUCT = 'PHYSICAL_PRODUCT';

    public function getLabel(): string
    {
        return match ($this) {
            self::CUSTOM_SERVICE => 'Jasa Cetak Custom',
            self::PHYSICAL_PRODUCT => 'Produk Fisik',
        };
    }
}
