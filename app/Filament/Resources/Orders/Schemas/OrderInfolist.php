<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pemesan')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('invoice_number')->label('No. Invoice')->weight('bold'),
                            TextEntry::make('customer_name'),
                            TextEntry::make('customer_phone'),
                            TextEntry::make('customer_email')->placeholder('-'),
                            TextEntry::make('delivery_method')->label('Metode Pengambilan'),
                            TextEntry::make('status')->badge(),
                            TextEntry::make('delivery_address')->placeholder('-')->columnSpanFull(),
                            TextEntry::make('total_amount')->money('IDR', locale: 'id'),
                            TextEntry::make('created_at')->dateTime('d M Y H:i'),
                            TextEntry::make('notes')->placeholder('-')->columnSpanFull(),
                        ]),
                    ]),

                Section::make('Rincian Pesanan')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->schema([
                                TextEntry::make('product.name')->label('Produk'),
                                TextEntry::make('variant_name')->placeholder('-'),
                                TextEntry::make('quantity')->label('Qty'),
                                TextEntry::make('width_cm')
                                    ->label('Ukuran')
                                    ->placeholder('-')
                                    ->formatStateUsing(fn (?string $state, $record) => $record->width_cm ? $record->width_cm.' × '.$record->height_cm.' cm' : null),
                                TextEntry::make('calculated_area')->suffix(' m²')->placeholder('-'),
                                TextEntry::make('unit_price')->money('IDR', locale: 'id'),
                                TextEntry::make('subtotal')->money('IDR', locale: 'id'),
                                TextEntry::make('finishing_note')->placeholder('-')->columnSpanFull(),
                                TextEntry::make('design_file_path')
                                    ->label('File Desain')
                                    ->url(fn ($state) => $state ? Storage::disk('public')->url($state) : null, true)
                                    ->icon('heroicon-o-paper-clip'),
                            ]),
                    ]),

                Section::make('Preflight Inspection')
                    ->description('Checklist verifikasi file artwork sebelum produksi (sesuai standar SOP).')
                    ->schema([
                        TextEntry::make('preflight')
                            ->label('')
                            ->state(fn () => "1. **Resolusi:** minimal 300 DPI pada ukuran cetak.\n2. **Color Space:** file dikonversi ke CMYK (bukan RGB).\n3. **Bleed:** sertakan area bleed 2-3 mm dari garis potong.\n4. **Font:** outline/convert font ke kurva sebelum di-upload.\n5. **File:** format PDF, TIFF, PSD, CDR, AI, atau ZIP (maks 50MB).")
                            ->markdown(),
                    ]),
            ]);
    }
}
