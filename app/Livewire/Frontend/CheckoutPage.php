<?php

namespace App\Livewire\Frontend;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\InvoiceService;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.frontend')]
class CheckoutPage extends Component
{
    #[Validate('required|string|max:150')]
    public string $customerName = '';

    #[Validate('required|regex:/^08[0-9]{8,12}$/')]
    public string $customerPhone = '';

    #[Validate('nullable|email|max:100')]
    public ?string $customerEmail = null;

    #[Validate('required|in:PICKUP,COURIER')]
    public string $deliveryMethod = 'PICKUP';

    #[Validate('nullable|string|max:1000')]
    public ?string $deliveryAddress = null;

    #[Validate('nullable|string|max:2000')]
    public ?string $notes = null;

    public function mount(): void
    {
        if (auth()->check()) {
            $user = auth()->user();
            $this->customerName = $user->name ?? '';
            $this->customerEmail = $user->email ?? null;
        }
    }

    #[Computed]
    public function cartItems(): array
    {
        return session('cart', []);
    }

    #[Computed]
    public function cartTotal(): int
    {
        return array_sum(array_column($this->cartItems, 'subtotal'));
    }

    public function submitOrder(): void
    {
        $cart = $this->cartItems;

        if (empty($cart)) {
            session()->flash('error', 'Keranjang masih kosong.');

            return;
        }

        if ($this->deliveryMethod === 'COURIER' && blank($this->deliveryAddress)) {
            $this->addError('deliveryAddress', 'Alamat pengiriman wajib diisi untuk pengiriman via kurir.');

            return;
        }

        $this->validate();

        $waService = app(WhatsAppService::class);
        $invoiceService = app(InvoiceService::class);

        $order = DB::transaction(function () use ($cart, $invoiceService) {
            $order = Order::create([
                'invoice_number' => $invoiceService->generate(),
                'customer_name' => $this->customerName,
                'customer_phone' => $this->customerPhone,
                'customer_email' => $this->customerEmail,
                'delivery_method' => $this->deliveryMethod,
                'delivery_address' => $this->deliveryMethod === 'COURIER' ? $this->deliveryAddress : null,
                'total_amount' => $this->cartTotal,
                'status' => 'PENDING_PAYMENT',
                'notes' => $this->notes,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'variant_name' => $item['variant_name'] ?? null,
                    'width_cm' => $item['width_cm'] ?? null,
                    'height_cm' => $item['height_cm'] ?? null,
                    'calculated_area' => $item['calculated_area'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                    'design_file_path' => $item['design_file_path'] ?? null,
                    'finishing_note' => $item['finishing_note'] ?? null,
                ]);
            }

            return $order;
        });

        $url = $waService->generateOrderSubmissionUrl($order);

        session()->forget('cart');
        $this->dispatch('cart-updated');

        $this->redirect($url);
    }

    public function render()
    {
        return view('livewire.frontend.checkout-page');
    }
}
