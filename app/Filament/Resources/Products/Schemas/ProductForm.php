<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\ProductCategory;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;


class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Select::make('category')
                    ->options(ProductCategory::class)
                    ->required(),
                FileUpload::make('image')
                    ->label('Gambar Produk')
                    ->image()
                    ->directory('products')
                    ->required(),    
                Textarea::make('description')
                    ->rows(4)
                    ->columnSpanFull(),
                TextInput::make('base_price')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp')
                    ->columnSpan(2),
                TextInput::make('min_size_m2')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->default(0.25)
                    ->helperText('Luas minimal tagihan (m²) — hanya untuk jasa berdimensi'),
                TextInput::make('unit_label')
                    ->required()
                    ->placeholder('m2, box, pcs, rim, set, roll'),
                Toggle::make('is_custom_dimension')
                    ->label('Produk berdimensi (m²)'),
                Toggle::make('requires_design_file')
                    ->label('Wajib upload file desain'),
                SpatieMediaLibraryFileUpload::make('images')
                    ->collection('images')
                    ->image()
                    ->imageEditor()
                    ->maxFiles(6),
            ]);
    }
}
