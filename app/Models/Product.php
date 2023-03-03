<?php

namespace App\Models;

use App\Enums\MediaType;
use App\Enums\DeliveryStatus;
use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        "name",
        "product_id",
        "brand_id",
        "category_id",
        "description",
        "retail_price",
        "offer_price",
        "reseller_price",
        "video_url",
        "status",
        "meta",
        "quantity",
        "box_price",
    ];

    protected $casts = [
        "status" => ProductStatus::class,
        "meta" => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function available(): bool
    {
        return $this->quantity > 0;
    }
}
