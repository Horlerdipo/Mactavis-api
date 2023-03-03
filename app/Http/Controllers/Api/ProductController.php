<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function __construct(private readonly ProductService $productService)
    {
    }

    public function getAllProducts(Request $request,): JsonResponse
    {
        if ($request->has("category")) {
            $products = $this->productService->getProductsByCategory($request->query("category"), $request->query("sort_by"));
        } elseif ($request->has("brand")) {
            $products = $this->productService->getProductsByBrand($request->query("brand"), $request->query("sort_by"));
        } elseif ($request->has("search")) {
            $products = $this->productService->searchProduct($request->query("search"), $request->query("sort_by"));
        } else {
            $products = $this->productService->getAllProducts($request->query("sort_by"));
        }

        return response()->json(data: [
            "status" => true,
            "data" => $products,
        ]);
    }

    public function getProduct($id): JsonResponse
    {
        $product = $this->productService->getProduct($id);
        if (!$product) {
            return abort(404);
        }

        return response()->json(data: [
            "status" => true,
            "data" => $product,
        ]);

    }

    public function getBrands(): JsonResponse
    {
        $brands = $this->productService->getAllBrands();
        return response()->json(data: [
            "status" => true,
            "data" => $brands,
        ]);
    }

    public function getCategories(): JsonResponse
    {
        $categories = $this->productService->getAllCategories();
        return response()->json(data: [
            "status" => true,
            "data" => $categories,
        ]);
    }
}
