<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\View;

class WhatsAppService
{
    public const CS_NUMBER = '6287860042888';

    public function generateOrderSubmissionUrl(Order $order): string
    {
        $text = View::make('whatsapp.order-submission', ['order' => $order])->render();

        return 'https://wa.me/'.self::CS_NUMBER.'?text='.rawurlencode($text);
    }

    public function generateCustomerUpdateUrl(Order $order, ?string $note = null): string
    {
        $text = View::make('whatsapp.status-update', ['order' => $order, 'note' => $note])->render();

        return 'https://wa.me/'.$this->normalizePhone($order->customer_phone).'?text='.rawurlencode($text);
    }

    public function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        } elseif (str_starts_with($digits, '62')) {
            // sudah benar
        } elseif (str_starts_with($digits, '8')) {
            $digits = '62'.$digits;
        }

        return $digits;
    }
}
