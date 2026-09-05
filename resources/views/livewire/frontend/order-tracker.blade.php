<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <flux:heading size="xl">Lacak Pesanan</flux:heading>
        <flux:text>Invoice: <span class="font-bold">{{ $order->invoice_number }}</span> · Status: 
            <flux:badge color="{{ $order->status->getColor() }}">{{ $order->status_label }}</flux:badge>
        </flux:text>
    </div>

    <flux:card class="space-y-6">
        <flux:heading size="lg">Status Produksi</flux:heading>
        <ol class="relative space-y-4 border-s border-zinc-200 ms-3">
            @foreach($this->timeline as $step)
                <li class="ms-6">
                    <span class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full {{ $step['achieved'] ? 'bg-indigo-600 text-white' : 'bg-zinc-200 text-zinc-500' }}">
                        @if($step['achieved'])
                            <flux:icon name="check" variant="micro" class="text-white" />
                        @endif
                    </span>
                    <div class="text-sm font-medium {{ $step['achieved'] ? 'text-zinc-900' : 'text-zinc-400 line-through' }}">
                        {{ $step['label'] }}
                    </div>
                </li>
            @endforeach
        </ol>
    </flux:card>

    <flux:card class="space-y-3">
        <flux:heading size="lg">Data Pemesan</flux:heading>
        <dl class="grid grid-cols-1 gap-2 text-sm sm:grid-cols-2">
            <div><dt class="text-zinc-500">Nama</dt><dd class="font-medium">{{ $order->customer_name }}</dd></div>
            <div><dt class="text-zinc-500">No. WhatsApp</dt><dd class="font-medium">{{ $order->customer_phone }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-zinc-500">Metode Pengambilan</dt><dd class="font-medium">{{ $order->delivery_method->getLabel() }}</dd></div>
            @if($order->delivery_address)
                <div class="sm:col-span-2"><dt class="text-zinc-500">Alamat</dt><dd class="font-medium">{{ $order->delivery_address }}</dd></div>
            @endif
            @if($order->notes)
                <div class="sm:col-span-2"><dt class="text-zinc-500">Catatan</dt><dd class="font-medium">{{ $order->notes }}</dd></div>
            @endif
        </dl>
    </flux:card>

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
                @foreach($order->items as $item)
                    <tr class="border-b">
                        <td class="py-2">
                            {{ $item->product->name }}
                            @if($item->variant_name)<div class="text-xs text-zinc-500">{{ $item->variant_name }}</div>@endif
                            @if(!empty($item->width_cm))<div class="text-xs text-zinc-500">{{ $item->width_cm }} × {{ $item->height_cm }} cm ({{ $item->calculated_area }} m²)</div>@endif
                            @if($item->finishing_note)<div class="text-xs text-zinc-500">✂️ {{ $item->finishing_note }}</div>@endif
                            @if($item->design_file_path)
                                <a href="{{ asset('storage/'.$item->design_file_path) }}" target="_blank" class="text-xs text-indigo-600 hover:underline">📎 File Desain</a>
                            @endif
                        </td>
                        <td class="py-2">{{ $item->quantity }}</td>
                        <td class="py-2 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="py-2 font-semibold">Total</td>
                    <td class="py-2 text-right font-bold text-indigo-600">{{ $order->total_amount_formatted }}</td>
                </tr>
            </tfoot>
        </table>
    </flux:card>
</div>
