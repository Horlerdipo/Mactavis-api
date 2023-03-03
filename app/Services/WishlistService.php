<?php

namespace App\Services;

use App\Models\Wishlist;
use Exception;
use Illuminate\Contracts\Pagination\Paginator;

class WishlistService
{
    const WISHLIST_PER_PAGE = 10;

    public function addProductToWishlist($productId, $userId): array
    {
        $response = [
            'status' => false,
            'wishlist' => null,
        ];

        try {
            $response['address'] = Wishlist::query()->create([
                'product_id' => $productId,
                'user_id' => $userId,
            ]);
            $response['status'] = true;
        } catch (Exception $exception) {
            log_error(exception: $exception, abort: false);
        }

        return $response;
    }

    public function removeProductFromWishlist($wishlistId, $userId): array
    {
        $response = [
            'status' => false,
            'message' => 'Something went wrong,please try again',
        ];

        try {
            $return = Wishlist::query()->where(function ($query) use ($wishlistId, $userId) {
                $query->where('user_id', $userId)->where('id', $wishlistId);
            })->delete();

            if ($return) {
                $response['status'] = true;
                $response['message'] = 'Wishlist Item deleted';
            } else {
                $response['status'] = false;
                $response['message'] = 'Unable to delete wishlist';
            }
        } catch (Exception $exception) {
            log_error(exception: $exception, abort: false);
        }

        return $response;
    }

    public function getWishlists($userId): Paginator
    {
        return Wishlist::query()->with(['product'])
            ->where('user_id', $userId)
            ->simplePaginate(self::WISHLIST_PER_PAGE);
    }
}
