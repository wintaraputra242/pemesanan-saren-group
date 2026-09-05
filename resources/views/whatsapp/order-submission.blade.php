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
{{ $index + 1 }}. *{{ $item->product->name }}*@if($item->variant_name) ({{ $item->variant_name }})@endif x{{ $item->quantity }}
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
