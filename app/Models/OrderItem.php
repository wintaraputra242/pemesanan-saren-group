<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_name',
        'width_cm',
        'height_cm',
        'calculated_area',
        'quantity',
        'unit_price',
        'subtotal',
        'design_file_path',
        'finishing_note',
    ];

    protected function casts(): array
    {
        return [
            'width_cm' => 'decimal:2',
            'height_cm' => 'decimal:2',
            'calculated_area' => 'decimal:2',
            'quantity' => 'integer',
            'unit_price' => 'integer',
            'subtotal' => 'integer',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getSubtotalFormattedAttribute(): string
    {
        return 'Rp '.number_format($this->subtotal, 0, ',', '.');
    }
}
