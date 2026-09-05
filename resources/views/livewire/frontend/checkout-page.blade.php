<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <flux:heading size="xl">Checkout</flux:heading>
        <flux:text>Lengkapi data pemesan, pesanan akan dikirim ke CS kami via WhatsApp.</flux:text>
    </div>

    @if(empty($this->cartItems))
        <flux:card class="py-12 text-center">
            <flux:heading>Keranjang kosong</flux:heading>
            <flux:text>Silakan pilih produk dari katalog terlebih dahulu.</flux:text>
            <flux:button variant="primary" href="{{ route('catalog') }}" wire:navigate class="mt-4">Lihat Katalog</flux:button>
        </flux:card>
    @else
        <flux:card class="space-y-4">
            <flux:heading size="lg">Rincian Pesanan</flux:heading>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-zinc-500 border-b">
                        <th class="py-2">Produk</th>
                        <th class="py-2">Qty</th>
                        <th class="py-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->cartItems as $item)
                        <tr class="border-b">
                            <td class="py-2">
                                {{ $item['product_name'] }}
                                @if(($item['variant_name'] ?? null))
                                    <div class="text-xs text-zinc-500">{{ $item['variant_name'] }}</div>
                                @endif
                                @if(($item['width_cm'] ?? null))
                                    <div class="text-xs text-zinc-500">{{ $item['width_cm'] }} × {{ $item['height_cm'] }} cm</div>
                                @endif
                            </td>
                            <td class="py-2">{{ $item['quantity'] }}</td>
                            <td class="py-2 text-right">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="py-2 font-semibold">Total</td>
                        <td class="py-2 text-right font-bold text-indigo-600">Rp {{ number_format($this->cartTotal, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </flux:card>

        <flux:card class="space-y-4">
            <flux:heading size="lg">Data Pemesan</flux:heading>

            <flux:field label="Nama Lengkap">
                <flux:input type="text" wire:model="customerName" />
                <flux:error name="customerName" />
            </flux:field>

            <flux:field label="No. WhatsApp">
                <flux:input type="text" wire:model="customerPhone" placeholder="08xxxxxxxxxx" />
                <flux:error name="customerPhone" />
            </flux:field>

            <flux:field label="Email (opsional)">
                <flux:input type="email" wire:model="customerEmail" />
                <flux:error name="customerEmail" />
            </flux:field>

            <flux:field label="Metode Pengambilan">
                <flux:radio.group wire:model.live="deliveryMethod" variant="segmented">
                    <flux:radio value="PICKUP" label="Ambil di Workshop" />
                    <flux:radio value="COURIER" label="Kirim via Kurir" />
                </flux:radio.group>
            </flux:field>

            @if($deliveryMethod === 'COURIER')
                <flux:field label="Alamat Pengiriman">
                    <flux:textarea wire:model="deliveryAddress" rows="3" />
                    <flux:error name="deliveryAddress" />
                </flux:field>
            @endif

            <flux:field label="Catatan (opsional)">
                <flux:textarea wire:model="notes" rows="3" placeholder="Catatan tambahan untuk CS..." />
            </flux:field>

            @if(session('error'))
                <flux:callout color="danger">{{ session('error') }}</flux:callout>
            @endif

            <flux:button variant="primary" class="w-full" wire:click="submitOrder" icon="chat-bubble-bottom-center-text">
                Pesan Sekarang via WhatsApp
            </flux:button>
            <flux:text class="text-center text-xs">Anda akan diarahkan ke WhatsApp untuk konfirmasi pemesanan.</flux:text>
        </flux:card>
    @endif
</div>
