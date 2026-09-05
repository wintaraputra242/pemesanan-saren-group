---
name: artwork-preflight-storage
description: Best practices and guidelines for handling large graphic design files (PDF, TIFF, PSD, CDR, AI), pre-flight inspection checklist, and secure storage in Laravel.
---

# Artwork Preflight & File Storage Skill

## 1. File Upload Configuration in Laravel
Agar Livewire dan Laravel dapat menampung upload artwork hingga 50MB:
- **`php.ini` Settings**:
  - `upload_max_filesize = 64M`
  - `post_max_size = 64M`
  - `max_execution_time = 300`
- **Livewire Config (`config/livewire.php`)**:
  - `'temporary_file_upload' => ['disk' => 'local', 'rules' => 'file|max:51200', 'directory' => 'livewire-tmp']`

## 2. Graphic File Type Reference & Validation
| Format | Ekstensi | Kebutuhan Penggunaan | Rule Validasi MIME |
| :--- | :--- | :--- | :--- |
| **PDF** | `.pdf` | Standar cetak universal untuk semua produk | `application/pdf` |
| **TIFF** | `.tif`, `.tiff` | Cetak Banner & Baliho resolusi tinggi | `image/tiff` |
| **Photoshop** | `.psd` | Desain raster bertingkat / jam dinding | `image/vnd.adobe.photoshop` |
| **CorelDraw** | `.cdr` | Format utama industri offset Bali | `application/x-cdr`, `application/octet-stream` |
| **Illustrator** | `.ai`, `.eps` | Vektor untuk sablon payung & cutting stiker | `application/postscript`, `application/pdf` |
| **Arsip** | `.zip`, `.rar` | Multi-file artwork & font pendukung | `application/zip`, `application/x-rar-compressed` |

## 3. Storage Architecture & Clean-up
- Simpan file yang sudah diverifikasi di `storage/app/public/artworks/{YYYY}/{MM}/{invoice_number}/`.
- Buat schedule command `php artisan saren:clean-temp-artworks` untuk menghapus file sementara yang tidak di-checkout lebih dari 7 hari.
