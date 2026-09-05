<?php

namespace Database\Seeders;

use App\Enums\ProductCategory;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    private function make(
        string $slug,
        string $name,
        ProductCategory $category,
        int $basePrice,
        string $unitLabel,
        bool $isCustomDimension = false,
        bool $requiresDesignFile = false,
        ?float $minSizeM2 = null,
        string $description = '',
        array $variants = [],
    ): void {
        $product = Product::updateOrCreate(
            ['slug' => $slug],
            [
                'name' => $name,
                'category' => $category,
                'base_price' => $basePrice,
                'unit_label' => $unitLabel,
                'is_custom_dimension' => $isCustomDimension,
                'requires_design_file' => $requiresDesignFile,
                'min_size_m2' => $minSizeM2,
                'description' => $description,
            ],
        );

        foreach ($variants as $variantName => $priceDiff) {
            ProductVariant::updateOrCreate(
                ['product_id' => $product->id, 'name' => $variantName],
                ['price_diff' => $priceDiff, 'is_active' => true],
            );
        }
    }

    public function run(): void
    {
        // Category A: Jasa Cetak Custom
        $this->make(
            'banner-spanduk',
            'Banner / Spanduk Fleksibel',
            ProductCategory::CUSTOM_SERVICE,
            25000,
            'm2',
            isCustomDimension: true,
            requiresDesignFile: true,
            minSizeM2: 0.25,
            description: 'Cetak spanduk promosi, baliho, backdrop, x-banner, roll-up banner. Luas minimal 0.25 m2.',
            variants: [
                'Flex China' => 0,
                'Flex Korea' => 15000,
            ],
        );

        $this->make(
            'stiker-custom',
            'Stiker Custom',
            ProductCategory::CUSTOM_SERVICE,
            35000,
            'm2',
            isCustomDimension: true,
            requiresDesignFile: true,
            minSizeM2: 0.25,
            description: 'Label kemasan makanan, stiker botol, merchandise komunitas, stiker decal. Pilihan bahan dan cutting.',
            variants: [
                'Vinyl Glossy' => 0,
                'Vinyl Doff' => 5000,
                'Bontax/Chromo' => -5000,
                'Transparan' => 10000,
            ],
        );

        $this->make(
            'kartu-nama',
            'Kartu Nama Eksklusif',
            ProductCategory::CUSTOM_SERVICE,
            55000,
            'box',
            requiresDesignFile: true,
            description: 'Kartu nama isi 100 pcs per box, art paper 260/310 gsm. Input file depan & belakang.',
            variants: [
                'Tanpa Laminasi' => 0,
                'Laminasi Doff 2 Sisi' => 15000,
                'Laminasi Glossy 2 Sisi' => 15000,
            ],
        );

        $this->make(
            'kartu-undangan',
            'Kartu Undangan',
            ProductCategory::CUSTOM_SERVICE,
            6000,
            'pcs',
            requiresDesignFile: true,
            description: 'Undangan pernikahan, upacara adat Bali, ulang tahun/event. Minimum order 50 pcs.',
        );

        $this->make(
            'payung-sablon',
            'Payung Sablon Promosi',
            ProductCategory::CUSTOM_SERVICE,
            45000,
            'pcs',
            requiresDesignFile: true,
            description: 'Payung lipat/standar sablon logo 1-2 sisi. Minimum order 12 pcs. Wajib upload file vektor.',
        );

        $this->make(
            'jam-dinding-custom',
            'Jam Dinding Desain Custom',
            ProductCategory::CUSTOM_SERVICE,
            50000,
            'pcs',
            requiresDesignFile: true,
            description: 'Jam dinding quartz diameter 25cm dengan custom cetak foto/desain.',
        );

        // Category B: Produk Fisik ATK
        $this->make(
            'tinta-printer',
            'Tinta Printer',
            ProductCategory::PHYSICAL_PRODUCT,
            65000,
            'botol',
            description: 'Tinta refill Dye/Pigment kompatibel (Epson, Canon, HP).',
            variants: [
                'Cyan' => 0,
                'Magenta' => 0,
                'Yellow' => 0,
                'Black' => 0,
            ],
        );

        $this->make('map-kertas', 'Map Kertas', ProductCategory::PHYSICAL_PRODUCT, 3000, 'pcs',
            description: 'Map folio kertas buffalo/stopmap untuk arsip dokumen.');

        $this->make('map-kancing-plastik', 'Map Kancing Plastik', ProductCategory::PHYSICAL_PRODUCT, 8000, 'pcs',
            description: 'Map plastik transparan dengan penutup kancing 1/2.');

        $this->make(
            'bingkai-foto',
            'Bingkai Foto Minimalis',
            ProductCategory::PHYSICAL_PRODUCT,
            15000,
            'pcs',
            description: 'Frame kayu/fiber kaca minimalis berbagai ukuran.',
            variants: [
                '2R' => 0,
                '3R' => 3000,
                '4R' => 5000,
                '5R' => 8000,
                '6R' => 10000,
                '7R' => 12000,
                '8R' => 15000,
                '10R' => 20000,
                'A4' => 25000,
                'A3' => 40000,
            ],
        );

        $this->make('materai-10000', 'Materai 10.000', ProductCategory::PHYSICAL_PRODUCT, 11000, 'keping',
            description: 'Materai tempel resmi Pos Indonesia 10.000 asli.');

        $this->make('kertas-print-a4', 'Kertas Print A4', ProductCategory::PHYSICAL_PRODUCT, 51000, 'rim',
            description: 'Kertas HVS 70/80 gsm, isi 500 lembar.');

        $this->make('kertas-print-f4', 'Kertas Print F4 (Folio)', ProductCategory::PHYSICAL_PRODUCT, 50000, 'rim',
            description: 'Kertas HVS 70/80 gsm ukuran Folio.');

        $this->make('kertas-print-a3', 'Kertas Print A3', ProductCategory::PHYSICAL_PRODUCT, 50000, 'rim',
            description: 'Kertas HVS ukuran A3.');

        $this->make('kertas-print-a5', 'Kertas Print A5', ProductCategory::PHYSICAL_PRODUCT, 60000, 'rim',
            description: 'Kertas HVS ukuran A5.');

        $this->make('paket-atk', 'Paket Alat Tulis Kantor', ProductCategory::PHYSICAL_PRODUCT, 15000, 'set',
            description: 'Set kombinasi: Pulpen Gel, Pensil 2B, dan Penghapus bebas debu.');

        $this->make('lakban-bening', 'Lakban Bening Besar', ProductCategory::PHYSICAL_PRODUCT, 12000, 'roll',
            description: 'Lakban isolasi bening tebal 48mm untuk packing.');
    }
}
