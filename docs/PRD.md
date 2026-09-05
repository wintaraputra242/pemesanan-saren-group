# PRODUCT REQUIREMENT DOCUMENT (PRD)
# Platform Web E-Commerce & Pemesanan Percetakan Digital — CV. Saren Grup

---

## 1. Executive Summary & Company Profile

### 1.1 Latar Belakang & Identitas Perusahaan
- **Nama Entitas**: CV. Saren Grup
- **Pendiri**: I MADE KRISNA, SST.Par., M.M.
- **Tahun Berdiri**: 
  - *2009*: Awal mula sebagai usaha warung internet (Warnet) bernama **Saren Komputer** di Sibang Kaja, Badung, Bali.
  - *2015*: Bertransformasi resmi menjadi badan usaha berbadan hukum **CV. Saren Grup** yang berfokus pada industri *digital printing* & percetakan digital profesional.
- **Kapasitas Operasional**:
  - Memiliki lebih dari 3 armada mesin cetak digital modern (Large Format Outdoor/Indoor Printer, High-Speed Digital Production Press, Mesin Sablon & Finishing).
  - Didukung oleh 12+ orang tenaga kerja terampil dan operator grafis profesional.
  - Telah bermitra aktif dengan 10+ entitas korporat, perhotelan, instansi swasta, dan dinas pemerintahan di Bali.
- **Lokasi Workshop & Kantor**: Jalan Raya Rijasa No. 6 Sibang Kaja, Kecamatan Abiansemal, Kabupaten Badung, Bali.
- **Kontak & Saluran Resmi**:
  - WhatsApp CS: `+62 878-6004-2888`
  - Email: `sarengrup@gmail.com`
  - Instagram: `@cvsaren_grup`

### 1.2 Visi & Misi Perusahaan
- **Visi**: Menjadi perusahaan percetakan digital terdepan yang mampu memenuhi segala kebutuhan cetak pelanggan dengan hasil berkualitas tinggi, terus meningkatkan produktivitas, serta membuka lapangan kerja yang berdaya saing tinggi.
- **Misi**:
  1. *Kepuasan Pelanggan sebagai Mitra*: Memberikan pelayanan terbaik dan solusi solutif untuk setiap pelanggan.
  2. *Cetakan Berkualitas & Tepat Waktu*: Menjamin presisi warna, daya tahan material, dan penyelesaian on-time sesuai komitmen.
  3. *Pengembangan Karyawan*: Memberdayakan dan meningkatkan kompetensi teknis sumber daya manusia internal.
  4. *Adopsi Teknologi*: Senantiasa mengintegrasikan teknologi mesin cetak terbaru dan digitalisasi manajemen operasional.

### 1.3 Tujuan Pengembangan Aplikasi
Membangun platform web berbasis **Laravel 11 + Livewire 3 + Filament v3** yang menjembatani pelanggan retail (B2C) maupun korporat (B2B) untuk:
1. Memesan produk cetak custom dengan kalkulasi dimensi ($m^2$) dan finishing otomatis secara *real-time*.
2. Memfasilitasi direct upload file artwork (PDF, TIFF, PSD, CDR, AI, ZIP) hingga 50MB.
3. Melakukan checkout otomatis via WhatsApp deep-link terstruktur ke nomor CS resmi.
4. Memberikan portal pelacakan status pesanan publik (*Live Order Tracker*).
5. Menyediakan backoffice manajemen pesanan, verifikasi file cetak, dan dashboard produksi terpadu bagi admin dan operator percetakan.

---

## 2. Target Pengguna (User Personas)

| Persona | Kebutuhan Utama | Ekspektasi Fitur |
| :--- | :--- | :--- |
| **Pelanggan Umum (B2C)** | Pesan spanduk event, stiker label usaha kecil, undangan upacara/nikah, cetak foto, beli ATK harian. | Live price calculator transparan, upload desain via HP gampang, direct checkout WA. |
| **Klien Korporat / Event Organizer (B2B)** | Pesan banner jumlah banyak, kartu nama karyawan per box, payung souvenir, rutin beli kertas/ATK kantor. | Opsi invoice terstruktur, input PO/catatan khusus, tracking status produksi live. |
| **Admin CS & Kasir** | Terima pesanan dari web, validasi pembayaran, input order offline (POS walk-in). | Filament order queue, generate invoice, direct button hubungi customer WA. |
| **Operator Produksi & Grafis** | Cek kesiapan artwork (resolusi, CMYK, bleed, font outline), update tahapan cetak dan finishing. | Preview & download file artwork resolusi tinggi, checklist pre-flight, ubah status order. |

---

## 3. Spesifikasi Lengkap Katalog Produk & Business Logic

### Kategori A: Jasa Cetak Custom (Dynamic Calculation & Artwork Upload)

#### 1. Banner / Spanduk Fleksibel (Outdoor & Indoor)
- **Karakteristik**: Cetak spanduk promosi, baliho, backdrop, x-banner, roll-up banner.
- **Base Price**: Rp 25.000 / $m^2$
- **Ketentuan Minimum**: Luas minimal pemesanan adalah $0.25\text{ m}^2$ (setara $50\text{ cm} \times 50\text{ cm}$). Ukuran di bawahnya tetap dihitung $0.25\text{ m}^2$.
- **Pilihan Bahan / Varian**:
  - `Flex China (Standar 280-340 gsm)`: $+\text{Rp } 0\text{ / m}^2$
  - `Flex Korea (High-Res Tebal 440 gsm)`: $+\text{Rp } 15.000\text{ / m}^2$
- **Opsi Finishing**:
  - *Mata Ayam (Ring Besi Pojok / Per Meter)*: Free / $+\text{Rp } 0$
  - *Selongsong (Kiri-Kanan / Atas-Bawah)*: $+\text{Rp } 0$
  - *Lipat Pres (Keliling Rapi)*: $+\text{Rp } 0$
  - *Polos (Tanpa Finishing / Potong Pas Gambar)*: $+\text{Rp } 0$
- **Formula Hitung**:
  $$\text{Luas Fisik } (m^2) = \frac{\text{Lebar (cm)} \times \text{Tinggi (cm)}}{10000}$$
  $$\text{Luas Terhitung } (m^2) = \max(0.25, \text{Luas Fisik})$$
  $$\text{Harga Satuan} = \text{Luas Terhitung} \times (\text{Base Price} + \text{Harga Varian Bahan})$$
  $$\text{Subtotal} = \text{Harga Satuan} \times \text{Quantity}$$

#### 2. Stiker Custom (Print & Cut)
- **Karakteristik**: Label kemasan makanan, stiker botol, merchandise komunitas, stiker decal.
- **Base Price**: Rp 35.000 / $m^2$ (Min charge $0.25\text{ m}^2$).
- **Pilihan Bahan**:
  - `Vinyl Glossy (Anti Air Mengkilap)`: Base Price (Rp 35.000/$m^2$)
  - `Vinyl Doff (Anti Air Elegan Matte)`: $+\text{Rp } 5.000\text{ / m}^2$
  - `Bontax / Chromo (Kertas Label Standar)`: $-\text{Rp } 5.000\text{ / m}^2$ (Rp 30.000/$m^2$)
  - `Transparan (Tembus Pandang)`: $+\text{Rp } 10.000\text{ / m}^2$ (Rp 45.000/$m^2$)
- **Opsi Cutting**:
  - *Die Cut (Potong Putus sesuai pola luar)*: $+\text{Rp } 5.000\text{ / m}^2$
  - *Kiss Cut (Setengah Putus tinggal kelupas lembaran)*: $+\text{Rp } 3.000\text{ / m}^2$
  - *Lembaran (Tanpa potong pola / potong kotak)*: Free

#### 3. Kartu Nama Eksklusif
- **Base Price**: Rp 55.000 / box (Isi 100 pcs, ukuran standar $9\text{ cm} \times 5.5\text{ cm}$ atau $10\text{ cm} \times 5\text{ cm}$).
- **Material**: Art Paper 260 / 310 gsm.
- **Varian Finishing**:
  - `Tanpa Laminasi`: $+\text{Rp } 0$ (Rp 55.000/box)
  - `Laminasi Doff 2 Sisi`: $+\text{Rp } 15.000$ (Rp 70.000/box)
  - `Laminasi Glossy 2 Sisi`: $+\text{Rp } 15.000$ (Rp 70.000/box)
- **Artwork**: Input file Depan & Belakang.

#### 4. Kartu Undangan
- **Base Price**: Rp 6.000 / pcs (Minimum order 50 pcs).
- **Karakteristik**: Undangan pernikahan, upacara adat Bali (Manusa Yadnya, Pitra Yadnya, Pawiwahan), undangan ulang tahun/event.
- **Input Form**: Upload desain jadi ATAU input detail teks acara (Nama Pasangan, Waktu, Tempat, Denah/QR Maps).

#### 5. Payung Sablon Promosi
- **Base Price**: Rp 45.000 / pcs (Minimum order 12 pcs / 1 lusin).
- **Karakteristik**: Payung lipat atau payung standar dengan sablon logo 1-2 sisi.
- **Artwork**: Wajib upload file vektor (AI, CDR, PDF, PNG resolusi tinggi transparan).

#### 6. Jam Dinding Desain Custom
- **Base Price**: Rp 50.000 / pcs (Ukuran Diameter 25 cm).
- **Karakteristik**: Jam dinding mesin quartz dengan custom cetak foto keluarga, logo perusahaan, kenang-kenangan pensiun/kelulusan.
- **Artwork**: Upload foto/desain format lingkaran.

---

### Kategori B: Produk Fisik & Alat Tulis Kantor (Direct Cart)

| No | Nama Produk | Harga Satuan | Satuan | Deskripsi & Varian |
| :---: | :--- | :--- | :---: | :--- |
| **7** | **Tinta Printer** | Rp 65.000 | Botol | Tinta refill Dye/Pigment kompatibel (Epson, Canon, HP) warna Cyan, Magenta, Yellow, Black. |
| **8a** | **Map Kertas** | Rp 3.000 | Pcs | Map folio kertas buffalo/stopmap untuk arsip dokumen. |
| **8b** | **Map Kancing Plastik** | Rp 8.000 | Pcs | Map plastik transparan dengan penutup kancing 1/2. |
| **9** | **Bingkai Foto Minimalis** | *Mulai Rp 15.000* | Pcs | Frame kayu/fiber kaca minimalis.<br>• 2R: Rp 15.000<br>• 3R: Rp 18.000<br>• 4R: Rp 20.000<br>• 5R: Rp 23.000<br>• 6R: Rp 25.000<br>• 7R: Rp 27.000<br>• 8R: Rp 30.000<br>• 10R: Rp 35.000<br>• A4: Rp 40.000<br>• A3: Rp 55.000 |
| **10** | **Materai 10.000** | Rp 11.000 | Keping | Materai tempel resmi Pos Indonesia 10.000 asli. |
| **11a**| **Kertas Print A4** | Rp 51.000 | Rim | Kertas HVS 70/80 gsm (Isi 500 lembar). |
| **11b**| **Kertas Print F4 (Folio)** | Rp 50.000 | Rim | Kertas HVS 70/80 gsm ukuran Folio. |
| **11c**| **Kertas Print A3** | Rp 50.000 | Rim | Kertas HVS ukuran A3. |
| **11d**| **Kertas Print A5** | Rp 60.000 | Rim | Kertas HVS ukuran A5. |
| **12** | **Paket Alat Tulis Kantor** | Rp 15.000 | Set | Set kombinasi: Pulpen Gel, Pensil 2B, dan Penghapus bebas debu. |
| **13** | **Lakban Bening Besar** | Rp 12.000 | Roll | Lakban isolasi bening tebal 48mm untuk packing. |

---

## 4. Arsitektur Teknis & Fitur Utama

```
+-----------------------------------------------------------------------+
|                           CLIENT BROWSER                              |
|   +---------------------------------------------------------------+   |
|   | Public Web (Blade + Livewire 3 + Alpine.js + Tailwind CSS)    |   |
|   |  - Landing Profile CV. Saren Grup                             |   |
|   |  - Realtime Dimension Calculator Component                    |   |
|   |  - File Artwork Drag & Drop (Livewire WithFileUploads)        |   |
|   |  - Slide-over Cart & Multi-Item Checkout Form                 |   |
|   |  - Public Order Tracker (/track/{invoice})                    |   |
|   +-------------------------------+-------------------------------+   |
+-----------------------------------|-----------------------------------+
                                    | HTTPS / Livewire AJAX
+-----------------------------------|-----------------------------------+
|                        LARAVEL 11 BACKEND                             |
|   +-------------------------------+-------------------------------+   |
|   | App Services & Logic                                          |   |
|   |  - PricingCalculatorService (Formula Luas & Minimum Size)      |   |
|   |  - WhatsAppService (Payload Formatter & Deep Link Builder)    |   |
|   |  - InvoiceNumberGenerator (SRN-YYYYMMDD-XXXX)                 |   |
|   +-------------------------------+-------------------------------+   |
|   | Admin Panel (Filament v3)                                     |   |
|   |  - OrderResource (Table Kanban, Filter Status, Preflight View)|   |
|   |  - ProductResource & Variant Manager                          |   |
|   |  - Action Button 'Hubungi Customer WA' (Auto-populated text) |   |
|   +-------------------------------+-------------------------------+   |
|   | Database (MySQL / PostgreSQL) & File Storage (Local/S3/R2)    |   |
+-----------------------------------------------------------------------+
```

---

## 5. Blueprint Struktur Data (Schema ERD)

### 5.1 Tabel `products`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `slug`: `VARCHAR(100) UNIQUE` (e.g. `banner-spanduk`, `kertas-print-a4`)
- `name`: `VARCHAR(255)`
- `category`: `ENUM('CUSTOM_SERVICE', 'PHYSICAL_PRODUCT')`
- `description`: `TEXT NULL`
- `base_price`: `INT UNSIGNED` (Harga dasar dalam Rupiah)
- `min_size_m2`: `DECIMAL(5, 2) DEFAULT 0.25 NULL` (Hanya untuk jasa berdimensi)
- `unit_label`: `VARCHAR(20)` (m2, box, rim, pcs, set, roll)
- `is_custom_dimension`: `BOOLEAN DEFAULT FALSE`
- `requires_design_file`: `BOOLEAN DEFAULT FALSE`
- `image_path`: `VARCHAR(255) NULL`
- `created_at`, `updated_at`: `TIMESTAMP`

### 5.2 Tabel `product_variants`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `product_id`: `BIGINT UNSIGNED FOREIGN KEY -> products(id) ON DELETE CASCADE`
- `name`: `VARCHAR(100)` (e.g. Flex China, Flex Korea, Vinyl Doff, 4R, A4)
- `price_diff`: `INT DEFAULT 0` (Penambah/pengurang dari `base_price`)
- `is_active`: `BOOLEAN DEFAULT TRUE`
- `created_at`, `updated_at`: `TIMESTAMP`

### 5.3 Tabel `orders`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `invoice_number`: `VARCHAR(50) UNIQUE` (Format: `SRN-20260830-001`)
- `customer_name`: `VARCHAR(150)`
- `customer_phone`: `VARCHAR(30)`
- `customer_email`: `VARCHAR(100) NULL`
- `delivery_method`: `ENUM('PICKUP', 'COURIER') DEFAULT 'PICKUP'`
- `delivery_address`: `TEXT NULL`
- `total_amount`: `BIGINT UNSIGNED`
- `status`: `ENUM('PENDING_PAYMENT', 'FILE_VERIFICATION', 'IN_PRODUCTION', 'FINISHING', 'READY_FOR_PICKUP', 'SHIPPED', 'COMPLETED', 'CANCELLED') DEFAULT 'PENDING_PAYMENT'`
- `notes`: `TEXT NULL`
- `created_at`, `updated_at`: `TIMESTAMP`

### 5.4 Tabel `order_items`
- `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- `order_id`: `BIGINT UNSIGNED FOREIGN KEY -> orders(id) ON DELETE CASCADE`
- `product_id`: `BIGINT UNSIGNED FOREIGN KEY -> products(id) ON DELETE CASCADE`
- `variant_name`: `VARCHAR(100) NULL`
- `width_cm`: `DECIMAL(8, 2) NULL`
- `height_cm`: `DECIMAL(8, 2) NULL`
- `calculated_area`: `DECIMAL(8, 2) NULL` ($m^2$)
- `quantity`: `INT UNSIGNED DEFAULT 1`
- `unit_price`: `INT UNSIGNED`
- `subtotal`: `BIGINT UNSIGNED`
- `design_file_path`: `VARCHAR(255) NULL`
- `finishing_note`: `VARCHAR(255) NULL`
- `created_at`, `updated_at`: `TIMESTAMP`

---

## 6. Non-Functional Requirements (NFR)
1. **Performa & Reaktivitas**: Perhitungan harga Livewire harus memiliki latensi di bawah 100ms dengan debouncing 250-300ms.
2. **Kapasitas Upload**: Mendukung upload file desain hingga 50MB per item menggunakan stream chunking Livewire.
3. **Mobile-First UX**: 80%+ pengguna memesan melalui smartphone; form kalkulator, tombol WA, dan upload harus dirancang satu jempol (*thumb-friendly*).
4. **Keamanan Data**: Sanitasi nomor telepon customer, validasi MIME-type file artwork ketat di sisi server untuk mencegah eksekusi skrip jahat.
