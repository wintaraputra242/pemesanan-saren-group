<?php

namespace App\Models;

use App\Enums\ProductCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'slug',
        'name',
        'category',
        'description',
        'base_price',
        'min_size_m2',
        'unit_label',
        'is_custom_dimension',
        'requires_design_file',
    ];

    protected function casts(): array
    {
        return [
            'category' => ProductCategory::class,
            'base_price' => 'integer',
            'min_size_m2' => 'decimal:2',
            'is_custom_dimension' => 'boolean',
            'requires_design_file' => 'boolean',
        ];
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function getBasePriceFormattedAttribute(): string
    {
        return 'Rp '.number_format($this->base_price, 0, ',', '.');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(640)
            ->nonQueued();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'category', 'base_price', 'unit_label'])
            ->useLogName('product');
    }
}
