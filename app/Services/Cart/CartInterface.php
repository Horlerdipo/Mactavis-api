<?php

namespace App\Services\Cart;

interface CartInterface
{
    public function setUp();

    public function addItemToCart(array $attributes = [], bool $withEvent = true);

    public function removeItemFromCart(string $itemHash, bool $withEvent = true);

    public function updateItemInCart(string $itemHash, array $attributes = [], $withEvent = true);

    public function getCartDetails();

    public function getItemTotalCost();

    public function getTotalItemInCart();

    public function getItemInCart(array $attributes, bool $complyAll = true);

    public function getOneItemInCart($identifier);

    public function clearCart();
}
