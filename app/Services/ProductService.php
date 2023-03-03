<?php

namespace App\Services;


use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductService
{
    const WISHLIST_PER_PAGE = 6;

    public function getProduct($productId): Model|Collection|Builder|array|null
    {
        return Product::with([
            "category",
            "brand",
            "media",
        ])->where("product_id", $productId)->first();
    }

    public function findProductImageByUUID($mediaId): Model|Builder|null
    {
        return Media::query()->where("uuid", $mediaId)->first();
    }
}
