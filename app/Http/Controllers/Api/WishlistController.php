<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AddWishlistRequest;
use App\Services\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct(private readonly WishlistService $wishlistService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $wishlists = $this->wishlistService->getWishlists(Auth::guard('sanctum')->id());

        return response()->json(data: [
            'status' => true,
            'message' => 'Wishlists Fetched Successfully',
            'data' => $wishlists,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddWishlistRequest $request): JsonResponse
    {
        $response = $this->wishlistService->addProductToWishlist($request->post('product_id'), Auth::guard('sanctum')->id());

        if ($response['status']) {
            return response()->json(data: [
                'status' => true,
                'message' => 'Wishlist created Successfully',
                'data' => null,
            ], status: 201);
        }

        return response()->json(data: [
            'status' => false,
            'message' => 'Something went wrong,please try again',
            'data' => null,
        ], status: 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $wishlistId): JsonResponse
    {
        $response = $this->wishlistService->removeProductFromWishlist($wishlistId, Auth::guard('sanctum')->id());

        if ($response['status']) {
            return response()->json(data: [
                'status' => true,
                'message' => $response['message'],
                'data' => null,
            ]);
        }

        return response()->json(data: [
            'status' => false,
            'message' => 'Something went wrong,please try again',
            'data' => null,
        ], status: 500);
    }
}
