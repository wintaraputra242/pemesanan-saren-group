<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;

class InvoiceService
{
    public function generate(): string
    {
        $day = date('Ymd');
        $prefix = 'SRN-'.$day.'-';
        $key = 'invoice-counter-'.$day;

        // Seed counter from the highest existing invoice number of the day,
        // then increment atomically so sequential calls keep counting.
        if (! Cache::has($key)) {
            $max = Order::where('invoice_number', 'like', $prefix.'%')->max('invoice_number');
            $start = $max ? (int) substr($max, strlen($prefix)) : 0;
            Cache::forever($key, $start);
        }

        $counter = (int) Cache::increment($key);

        return $prefix.str_pad((string) $counter, 4, '0', STR_PAD_LEFT);
    }
}
