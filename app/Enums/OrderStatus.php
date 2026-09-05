<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasColor, HasLabel
{
    case PENDING_PAYMENT = 'PENDING_PAYMENT';
    case FILE_VERIFICATION = 'FILE_VERIFICATION';
    case IN_PRODUCTION = 'IN_PRODUCTION';
    case FINISHING = 'FINISHING';
    case READY_FOR_PICKUP = 'READY_FOR_PICKUP';
    case SHIPPED = 'SHIPPED';
    case COMPLETED = 'COMPLETED';
    case CANCELLED = 'CANCELLED';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING_PAYMENT => 'Menunggu Pembayaran',
            self::FILE_VERIFICATION => 'Verifikasi File',
            self::IN_PRODUCTION => 'Sedang Dicetak',
            self::FINISHING => 'Tahap Finishing',
            self::READY_FOR_PICKUP => 'Siap Diambil',
            self::SHIPPED => 'Sedang Dikirim',
            self::COMPLETED => 'Selesai',
            self::CANCELLED => 'Dibatalkan',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING_PAYMENT => 'warning',
            self::FILE_VERIFICATION => 'info',
            self::IN_PRODUCTION, self::FINISHING, self::SHIPPED => 'primary',
            self::READY_FOR_PICKUP => 'success',
            self::COMPLETED => 'gray',
            self::CANCELLED => 'danger',
        };
    }
}
