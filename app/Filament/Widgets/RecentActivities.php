<?php

namespace App\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class RecentActivities extends TableWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Activity::query()->latest()->limit(8))
            ->paginated(false)
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->since()
                    ->sortable(),
                TextColumn::make('log_name')
                    ->label('Modul')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst((string) $state))
                    ->color(fn (string $state): string => match ($state) {
                        'product' => 'indigo',
                        'order' => 'success',
                        default => 'zinc',
                    }),
                TextColumn::make('description')
                    ->label('Aktivitas'),
                TextColumn::make('causer.name')
                    ->label('Oleh')
                    ->placeholder('sistem'),
            ]);
    }
}
