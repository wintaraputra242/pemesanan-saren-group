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
