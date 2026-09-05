---
name: filament-production-pipeline
description: Filament v3 patterns for print production order management, status badges, artwork inspection infolists, and contextual customer WhatsApp actions.
---

# Filament V3 Print Production Pipeline Skill

## 1. Status Badges & Enums
Definisikan Enum `OrderStatus` dengan interface `Filament\Support\Contracts\HasColor` dan `HasLabel`:

```php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel, HasColor
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

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING_PAYMENT => 'warning',
            self::FILE_VERIFICATION => 'info',
            self::IN_PRODUCTION, self::FINISHING => 'primary',
            self::READY_FOR_PICKUP, self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
        };
    }
}
```

## 2. Order Resource Table & Actions Pattern
Tambahkan action WhatsApp langsung di table Filament:

```php
namespace App\Filament\Resources;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use App\Models\Order;
use App\Services\WhatsAppService;

public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('invoice_number')->searchable()->copyable()->weight('bold'),
            TextColumn::make('customer_name')->searchable(),
            TextColumn::make('customer_phone')->copyable(),
            TextColumn::make('total_amount')->money('IDR', locale: 'id')->sortable(),
            TextColumn::make('status')->badge(),
            TextColumn::make('created_at')->dateTime('d M Y H:i')->sortable(),
        ])
        ->actions([
            Action::make('sendWhatsApp')
                ->label('WA Customer')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('success')
                ->url(fn (Order $record): string => app(WhatsAppService::class)->generateCustomerUpdateUrl($record), shouldOpenInNewTab: true),
        ]);
}
```
