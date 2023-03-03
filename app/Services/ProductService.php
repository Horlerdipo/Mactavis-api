<?php

namespace App\Services;


use App\Enums\SortBy;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{

    const PRODUCT_PER_PAGE = 6;

    public function getAllProducts($sortBy = null): Paginator
    {
        $products = Product::query()->with(['category', 'brand', 'media']);

        $products = match ($sortBy) {
            SortBy::ALPHABETICAL_DESC->value => $products->orderBy("name", "desc"),
            SortBy::PRICE_DESC->value => $products->orderBy("offer_price", "desc"),
            SortBy::PRICE_ASC->value => $products->orderBy("offer_price"),
            default => $products->orderBy("name"),
        };

        return $products->simplePaginate(self::PRODUCT_PER_PAGE)->withQueryString();
    }

    public function getProductsByCategory($categoryId, $sortBy = null): Paginator
    {
        $products = Product::query()->with(['category', 'brand', 'media'])
            ->where("category_id", $categoryId);

        $products = match ($sortBy) {
            SortBy::ALPHABETICAL_DESC->value => $products->orderBy("name", "desc"),
            SortBy::PRICE_DESC->value => $products->orderBy("offer_price", "desc"),
            SortBy::PRICE_ASC->value => $products->orderBy("offer_price"),
            default => $products->orderBy("name"),
        };

        return $products->simplePaginate(self::PRODUCT_PER_PAGE)->withQueryString();
    }

    public function getProductsByBrand($brandId, $sortBy = null): Paginator
    {

        $products = Product::query()->with(['category', 'brand', 'media'])
            ->where("brand_id", $brandId);

        $products = match ($sortBy) {
            SortBy::ALPHABETICAL_DESC->value => $products->orderBy("name", "desc"),
            SortBy::PRICE_DESC->value => $products->orderBy("offer_price", "desc"),
            SortBy::PRICE_ASC->value => $products->orderBy("offer_price"),
            default => $products->orderBy("name"),
        };

        return $products->simplePaginate(self::PRODUCT_PER_PAGE)->withQueryString();
    }

    public function searchProduct($searchParam, $sortBy = null): Paginator
    {
        $products = Product::query()
            ->where('product_id', 'LIKE', "%{$searchParam}%")
            ->orWhere('name', 'LIKE', "%{$searchParam}%")
            ->orWhere('description', 'LIKE', "%{$searchParam}%");

        $products = match ($sortBy) {
            SortBy::ALPHABETICAL_DESC->value => $products->orderBy("name", "desc"),
            SortBy::PRICE_DESC->value => $products->orderBy("offer_price", "desc"),
            SortBy::PRICE_ASC->value => $products->orderBy("offer_price"),
            default => $products->orderBy("name"),
        };

        return $products->simplePaginate(self::PRODUCT_PER_PAGE)->withQueryString();
    }

    public function getAllCategories(): Paginator
    {
        return Category::query()->where("status", true)->simplePaginate(self::PRODUCT_PER_PAGE);
    }

    public function getAllBrands(): Paginator
    {
        return Brand::query()->where("status", true)->simplePaginate(self::PRODUCT_PER_PAGE);
    }
}
