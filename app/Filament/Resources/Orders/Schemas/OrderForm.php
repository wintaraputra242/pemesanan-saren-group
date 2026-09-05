<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\DeliveryMethod;
use App\Enums\OrderStatus;
use App\Models\ProductVariant;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pemesan')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('invoice_number')
                                ->label('No. Invoice')
                                ->default(fn (): string => 'SRN-'.now()->format('Ymd').'-'.str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT))
                                ->unique(ignoreRecord: true)
                                ->required(),
                            TextInput::make('customer_name')
                                ->label('Nama Pemesan')
                                ->required(),
                            TextInput::make('customer_phone')
                                ->label('No. WhatsApp')
                                ->tel()
                                ->required(),
                            TextInput::make('customer_email')
                                ->label('Email')
                                ->email(),
                            Select::make('delivery_method')
                                ->label('Metode Pengambilan')
                                ->options(DeliveryMethod::class)
                                ->default(DeliveryMethod::PICKUP->value)
                                ->required()
                                ->live(),
                            Select::make('status')
                                ->options(OrderStatus::class)
                                ->default(OrderStatus::PENDING_PAYMENT->value)
                                ->required(),
                            TextInput::make('delivery_address')
                                ->label('Alamat Pengiriman')
                                ->visible(fn (callable $get): bool => $get('delivery_method') === DeliveryMethod::COURIER->value)
                                ->required(fn (callable $get): bool => $get('delivery_method') === DeliveryMethod::COURIER->value)
                                ->columnSpanFull(),
                        ]),
                    ]),

                Section::make('Rincian Pesanan')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                $data['subtotal'] = max(0, (int) ($data['quantity'] ?? 0)) * max(0, (int) ($data['unit_price'] ?? 0));

                                return $data;
                            })
                            ->label('')
                            ->columns(4)
                            ->columnSpanFull()
                            ->addActionLabel('Tambah Item')
                            ->schema([
                                Select::make('product_id')
                                    ->label('Produk')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(fn (callable $set) => $set('variant_name', null))
                                    ->required(),
                                Select::make('variant_name')
                                    ->label('Varian')
                                    ->options(fn (callable $get): array => ProductVariant::query()
                                        ->where('product_id', $get('product_id'))
                                        ->where('is_active', true)
                                        ->pluck('name', 'name')
                                        ->all())
                                    ->nullable(),
                                TextInput::make('quantity')
                                    ->label('Qty')
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1)
                                    ->required(),
                                Grid::make(2)->schema([
                                    TextInput::make('width_cm')
                                        ->label('Lebar (cm)')
                                        ->numeric()
                                        ->minValue(0),
                                    TextInput::make('height_cm')
                                        ->label('Tinggi (cm)')
                                        ->numeric()
                                        ->minValue(0),
                                ]),
                                TextInput::make('finishing_note')
                                    ->label('Finishing'),
                                Toggle::make('has_design_file')
                                    ->label('Ada file desain?')
                                    ->live()
                                    ->dehydrated(false)
                                    ->afterStateHydrated(fn (Toggle $component, $record) => $component->state((bool) $record?->design_file_path))
                                    ->formatStateUsing(fn ($record) => (bool) $record?->design_file_path),
                                FileUpload::make('design_file_path')
                                    ->label('File Desain')
                                    ->disk('public')
                                    ->directory('designs')
                                    ->preserveFilenames()
                                    ->visible(fn (callable $get): bool => (bool) $get('has_design_file'))
                                    ->maxSize(51200),
                                TextInput::make('unit_price')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->minValue(0)
                                    ->prefix('Rp')
                                    ->default(0)
                                    ->required(),
                            ])
                            ->live()
                            ->afterStateUpdated(fn (callable $set, callable $get) => OrderForm::recalculate($set, $get)),
                    ]),

                Section::make('Ringkasan')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('total_amount')
                                ->label('Total')
                                ->numeric()
                                ->prefix('Rp')
                                ->default(0)
                                ->readOnly()
                                ->dehydrated(true),
                            Textarea::make('notes')
                                ->label('Catatan')
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }

    public static function recalculate(callable $set, callable $get): void
    {
        $total = collect($get('items') ?? [])->sum(
            fn (array $item): int => max(0, (int) ($item['quantity'] ?? 1)) * max(0, (int) ($item['unit_price'] ?? 0)),
        );

        $set('total_amount', $total);
    }
}
