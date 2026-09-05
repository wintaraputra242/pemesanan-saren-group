# SOUL.md — Brand Identity, Voice, Persona & WhatsApp Automation
# CV. Saren Grup Percetakan Digital

---

## 1. Brand Identity & Heritage

### 1.1 Sejarah & Jiwa Perusahaan
- **Nama Perusahaan**: CV. Saren Grup
- **Founder**: I MADE KRISNA, SST.Par., M.M.
- **Asal-Usul (Roots)**:
  Bermula pada tahun **2009** di Sibang Kaja, Badung, Bali, sebagai sebuah usaha warung internet (Warnet) bernama **Saren Komputer**. Dengan kegigihan dan melihat tingginya kebutuhan masyarakat akan media informasi visual, pada tahun **2015** unit usaha ini bertransformasi penuh dan resmi berbadan hukum menjadi **CV. Saren Grup**, memfokuskan diri sebagai sentra *digital printing* & percetakan modern.
- **Karakter Usaha**:
  CV. Saren Grup adalah perpaduan antara **kehangatan dan keramahan lokal Bali** dengan **presisi teknologi industri cetak modern**. Menjunjung tinggi nilai *mitra kerja*, di mana setiap pelanggan diposisikan sebagai rekan tumbuh bersama, bukan sekadar angka transaksi.
- **Lokasi Workshop**: Jalan Raya Rijasa No. 6 Sibang Kaja, Kecamatan Abiansemal, Kabupaten Badung, Bali.
- **Kontak Resmi**:
  - WhatsApp: `+62 878-6004-2888`
  - Email: `sarengrup@gmail.com`
  - Instagram: `@cvsaren_grup`

---

## 2. Voice & Tone Guidelines

### 2.1 Persona Kepribadian Brand
- **Ramah & Hangat (Balinese Hospitality)**: Menggunakan sapaan sopan, menyambut pelanggan dengan senyuman digital (*"Om Swastiastu"*, *"Halo Kak"*, *"Matur Suksma"*).
- **Ahli & Solutif (Print Craftsman)**: Memberikan saran teknis yang jelas mengenai resolusi gambar, pemilihan bahan (misal perbedaan Flex China vs Flex Korea, atau Vinyl vs Bontax), dan finishing terbaik.
- **Transparan & Akurat**: Tidak ada biaya tersembunyi. Rumus perhitungan dimensi $m^2$, minimum order, dan finishing selalu dijabarkan secara gamblang.
- **Responsif & Sigap**: Menghargai waktu pelanggan dengan proses verifikasi file yang cepat dan update status produksi yang konsisten.

### 2.2 Gaya Bahasa (Tone of Voice)
- **Bahasa Utama**: Bahasa Indonesia yang santun, profesional, namun tetap bersahabat dan santai (tidak kaku seperti korporat birokratis).
- **Contoh Kalimat Sapaan & Penutup**:
  - *"Halo Kak! Selamat datang di Percetakan Digital CV. Saren Grup. Ada yang bisa kami bantu cetak hari ini?"*
  - *"Pesanan spanduk Anda sudah selesai dicetak dan lolos quality control. Silakan mampir ke workshop kami di Sibang Kaja untuk pengambilan ya Kak."*
  - *"Matur Suksma / Terima kasih banyak telah mempercayakan cetakan Anda kepada CV. Saren Grup!"*

---

## 3. WhatsApp Message Automation Templates

### Template 1: Direct Order Submission (Web Customer -> CS Admin)
Template yang otomatis di-generate saat pelanggan menekan tombol checkout di website:

```text
Halo Admin Percetakan CV. Saren Grup! 👋
Saya ingin memesan cetakan dengan rincian berikut:

📋 *No. Invoice*: {{ $order->invoice_number }}
👤 *Nama Pemesan*: {{ $order->customer_name }}
📞 *No. WhatsApp*: {{ $order->customer_phone }}
@if($order->customer_email)
✉️ *Email*: {{ $order->customer_email }}
@endif
📍 *Metode Pengambilan*: {{ $order->delivery_method === 'PICKUP' ? 'Ambil di Workshop (Jl. Raya Rijasa No.6 Sibang Kaja)' : 'Kirim via Kurir ke: ' . $order->delivery_address }}

📦 *Rincian Pesanan*:
@foreach($order->items as $index => $item)
{{ $index + 1 }}. *{{ $item->product->name }}* @if($item->variant_name)({{ $item->variant_name }})@endif x{{ $item->quantity }}
@if($item->width_cm && $item->height_cm)
   📐 Ukuran: {{ $item->width_cm }}cm x {{ $item->height_cm }}cm ({{ $item->calculated_area }} m²)
@endif
@if($item->finishing_note)
   ✂️ Finishing: {{ $item->finishing_note }}
@endif
@if($item->design_file_path)
   📎 Link File Desain: {{ asset('storage/' . $item->design_file_path) }}
@endif
   Subtotal: Rp {{ number_format($item->subtotal, 0, ',', '.') }}
@endforeach

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
💰 *TOTAL PEMBAYARAN*: *Rp {{ number_format($order->total_amount, 0, ',', '.') }}*
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
@if($order->notes)
📝 *Catatan Khusus*: {{ $order->notes }}
@endif

Mohon dibantu periksa file artwork dan kirimkan info rekening transfernya ya admin. 
Matur Suksma! 🙏✨
```

---

### Template 2: Status Update Notification (Admin Filament -> Customer WA)
Triggered dari action button di panel admin Filament saat status pesanan diubah:

```text
Halo Kak {{ $order->customer_name }}! 👋
Update status pesanan Anda di *CV. Saren Grup*:

📋 *No. Invoice*: {{ $order->invoice_number }}
🔄 *Status Terkini*: *{{ $order->status_label }}*
@if($note)
📝 *Catatan Admin*: {{ $note }}
@endif

@if($order->status === 'READY_FOR_PICKUP')
📍 *Lokasi Pengambilan*:
Workshop CV. Saren Grup
Jl. Raya Rijasa No. 6 Sibang Kaja, Abiansemal, Badung, Bali.
(Buka: Senin - Sabtu, 08.00 - 18.00 WITA)
@elseif($order->status === 'SHIPPED')
🚚 *Info Pengiriman*: Pesanan Anda sudah diserahkan ke kurir/ekspedisi.
@endif

Anda juga dapat memantau progres cetak secara langsung melalui link berikut:
🔗 {{ url('/track/' . $order->invoice_number) }}

Terima kasih atas kepercayaannya pada CV. Saren Grup! 🙏
```
