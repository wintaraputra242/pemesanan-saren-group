# AGENT_GUIDELINES.md — Vibecoding Architecture & Development Guidelines
# Project: Web Pemesanan Percetakan Digital CV. Saren Grup (Laravel 11 + Livewire 3 + Filament v3)

---

## 1. Technical Stack & Foundation
- **PHP Version**: `^8.2`
- **Framework**: `Laravel 11.x`
- **Frontend & Reactivity**: `Livewire 3.x` + `Alpine.js` + `Tailwind CSS 3.4`
- **Admin & Backoffice Panel**: `Filament v3.x` (Table Builder, Form Builder, Infolists, Action Modals)
- **Database Engine**: `MySQL 8.0` / `PostgreSQL 16` / `SQLite 3`
- **File Storage**: Local Public Disk (`storage/app/public`) dengan opsi Cloudflare R2 / S3 untuk artwork besar.

---

## 2. Directory & Namespace Structure

Pastikan struktur file mengikuti konvensi Laravel 11 & Livewire 3 berikut:

```
app/
├── Enums/
│   ├── ProductCategory.php       # CUSTOM_SERVICE, PHYSICAL_PRODUCT
│   ├── OrderStatus.php           # PENDING_PAYMENT, FILE_VERIFICATION, IN_PRODUCTION, dll.
│   └── DeliveryMethod.php        # PICKUP, COURIER
├── Filament/
│   ├── Resources/
│   │   ├── OrderResource.php     # Order management, kanban/table, filter status
│   │   ├── OrderResource/
│   │   │   └── Pages/
│   │   │       ├── ListOrders.php
│   │   │       ├── ViewOrder.php
│   │   │       └── EditOrder.php
│   │   └── ProductResource.php   # Catalog, base price, & variant manager
├── Livewire/
│   ├── Frontend/
│   │   ├── ProductCatalog.php    # Filter kategori, search & list produk
│   │   ├── BannerCalculator.php  # Live dimension calc khusus banner
│   │   ├── StickerCalculator.php # Live calc stiker & cutting method
│   │   ├── StandardCustomOrder.php # Kartu nama, undangan, jam dinding, payung
│   │   ├── CartDrawer.php        # Slide-over cart state & summary
│   │   ├── CheckoutPage.php      # Form kontak, delivery, & submit order
│   │   └── OrderTracker.php      # Live invoice tracking (/track/{invoice})
│   └── Traits/
│       └── HasPrintingMath.php   # Trait perhitungan dimensi & minimum area
├── Models/
│   ├── Order.php
│   ├── OrderItem.php
│   ├── Product.php
│   └── ProductVariant.php
└── Services/
    ├── InvoiceService.php        # Generator No. Invoice (SRN-YYYYMMDD-XXXX)
    ├── PricingCalculatorService.php # Central calculation engine
    └── WhatsAppService.php       # WA Message payload generator & deep link
```

---

## 3. Strict Coding Conventions for AI Agents

### 3.1 Livewire 3 Form & Reactivity Rules
1. **Gunakan Livewire Attributes Modern**:
   - Gunakan `#[Validate]` attribute langsung di property komponen.
   - Gunakan `#[Computed]` untuk getter perhitungan reaktif.
   - Gunakan `#[Url]` untuk query strings seperti filter dan search.
2. **Optimasi Livewire Reaktivitas**:
   - Selalu pasang `wire:model.live.debounce.250ms` pada input lebar dan tinggi untuk mencegah render berlebih saat pengguna mengetik di smartphone.
3. **Upload File Artwork**:
   - Gunakan trait `Livewire\WithFileUploads`.
   - Validasi ketat: `#[Validate('file|max:51200|mimes:pdf,tiff,tif,jpg,jpeg,png,zip,rar,cdr,psd,ai')]`.
   - Berikan visual upload progress indicator (`wire:loading wire:target="designFile"`).

### 3.2 Filament v3 Backoffice Rules
1. **Status Badge Colors**:
   - Terapkan warna status di `OrderResource`:
     ```php
     Tables\Columns\TextColumn::make('status')
         ->badge()
         ->color(fn (OrderStatus $state): string => match ($state) {
             OrderStatus::PENDING_PAYMENT => 'warning',
             OrderStatus::FILE_VERIFICATION => 'info',
             OrderStatus::IN_PRODUCTION, OrderStatus::FINISHING => 'primary',
             OrderStatus::READY_FOR_PICKUP, OrderStatus::COMPLETED => 'success',
             OrderStatus::CANCELLED => 'danger',
         })
     ```
2. **WhatsApp Action Button**:
   - Sediakan custom action di tabel dan halaman view untuk membuka chat WhatsApp customer dengan pesan kontekstual status pesanan terkini (`WhatsAppService::generateCustomerUpdateUrl($order)`).
3. **Download & Preview File Artwork**:
   - Tampilkan preview gambar dan tombol download langsung untuk file TIFF/PDF/ZIP yang diupload customer di `OrderResource::infolist()`.

### 3.3 Database & Eloquent Best Practices
1. **Format Keuangan**:
   - Simpan semua nominal uang dalam integer Rupiah utuh (misal `25000`, `55000`) di database. Jangan gunakan float untuk menghindari pembulatan presisi desimal.
2. **Luas Area Cetak**:
   - Simpan luas $m^2$ dalam kolom `decimal(8, 2)`.
3. **Relasi & Integrity**:
   - Semua foreign key pada `order_items` dan `product_variants` wajib menggunakan `constrained()->cascadeOnDelete()`.

---

## 4. Testing & Verification Checklist
Sebelum menandai implementasi selesai:
- [ ] Validasi perhitungan banner: Ukuran $30\text{ cm} \times 40\text{ cm}$ ($0.12\text{ m}^2$) harus tetap dibebankan minimal $0.25\text{ m}^2$ ($0.25 \times \text{Rp } 25.000 = \text{Rp } 6.250$).
- [ ] Validasi upload file: Coba upload file PDF dan ZIP berukuran 10-40MB, pastikan masuk ke `storage/app/public/artworks` dan link-nya valid.
- [ ] Validasi WhatsApp Generator: Pastikan format encoded URI tidak merusak baris baru (`%0A`) dan karakter khusus.
- [ ] Validasi Tracking Publik: Halaman `/track/SRN-xxxx` harus menampilkan timeline status akurat tanpa memerlukan login customer.
