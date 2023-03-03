<?php

namespace App\Services\Cart\Providers;

use App\Services\Cart\CartInterface;
use Jackiedo\Cart\Cart;
use Jackiedo\Cart\Details;
use Jackiedo\Cart\Item;

class LaravelCart implements CartInterface
{
    public Cart $cart;

    public function __construct()
    {
        $this->setUp();
    }

    public function setUp()
    {
        $this->cart = new Cart();
    }

    public function addItemToCart(array $attributes = [], $withEvent = true): ?Item
    {
        return $this->cart->addItem($attributes, $withEvent);
    }

    public function removeItemFromCart($itemHash, $withEvent = true): Cart
    {
        return $this->cart->removeItem($itemHash, $withEvent);
    }

    public function updateItemInCart($itemHash, $attributes = [], $withEvent = true): ?Item
    {
        return $this->cart->updateItem($itemHash, $attributes, $withEvent);
    }

    public function getCartDetails(): Details
    {
        return $this->cart->getDetails();
    }

    public function getItemTotalCost()
    {
        return $this->cart->getDetails(false)->items_subtotal;
    }

    public function getTotalItemInCart()
    {
        return $this->cart->getDetails(false)->quantities_sum;
    }

    public function getItemInCart($attributes, $complyAll = true): array
    {
        return $this->cart->getItems($attributes, $complyAll);
    }

    public function getOneItemInCart($identifier): Item
    {
        return $this->cart->getItem($identifier);
    }

    public function clearCart(): Cart
    {
        return $this->cart->clearItems();
    }
}
