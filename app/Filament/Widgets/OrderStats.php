<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        return [
            Stat::make('Menunggu Verifikasi', Order::where('status', OrderStatus::PENDING_PAYMENT->value)->count())
                ->description('Pesanan belum dibayar')
                ->color('warning')
                ->icon('heroicon-o-clock'),
            Stat::make('Dalam Produksi', Order::where('status', OrderStatus::IN_PRODUCTION->value)->count())
                ->description('Sedang dikerjakan')
                ->color('info')
                ->icon('heroicon-o-cog-6-tooth'),
            Stat::make('Siap Diambil', Order::where('status', OrderStatus::READY_FOR_PICKUP->value)->count())
                ->description('Menunggu pelanggan')
                ->icon('heroicon-o-archive-box'),
        ];
    }
}
