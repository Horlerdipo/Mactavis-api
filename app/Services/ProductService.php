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

    public function addProductToWishlist($productId, $userId): Builder|Model|null
    {
        return Wishlist::query()->create([
            "product_id" => $productId,
            "user_id" => $userId,
        ]);
    }

    public function removeProductFromWishlist($productId, $userId): int|null
    {
        return Wishlist::query()->where(function ($query) use ($productId, $userId) {
            $query->where("user_id", $userId)->where("product_id", $productId);
        }
        )->delete();
    }

    public function getWishlist($productId, $userId): Model|Builder|null
    {
        return Wishlist::query()->where(function ($query) use ($productId, $userId) {
            $query->where("user_id", $userId)->where("product_id", $productId);
        }
        )->first();
    }

    public function getWishlists($userId): Paginator
    {
        return Wishlist::query()->with(["product"])
            ->where("user_id", $userId)
            ->simplePaginate(self::WISHLIST_PER_PAGE);
    }

    public function findProductImageByUUID($mediaId): Model|Builder|null
    {
        return Media::query()->where("uuid", $mediaId)->first();
    }
}
