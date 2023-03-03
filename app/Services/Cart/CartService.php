<?php

namespace App\Services\Cart;


use App\Enums\CartEnum;
use App\Models\Address;
use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CartService
{

    public function __construct(public CartInterface $cart)
    {
    }

    public function addItemToCart(array $attributes, bool $event, bool $addOriginalBox = false)
    {
        $previousItem = $this->cart->getItemInCart(['id' => $attributes['id']]);
        if (empty($previousItem)) {

            session([request()->cookie('phone_number') . '_items_in_cart' => 1]);
            $cart = $this->cart->addItemToCart($attributes, $event);

        } else {

            $attributes['quantity'] = $this->cart->getOneItemInCart(key($previousItem))->getQuantity() + $attributes["quantity"];
            session()->increment('count', $attributes["quantity"]);
//            session([request()->cookie('phone_number') . '_items_in_cart' => $this->cart->getCartDetails()->quantities_sum]);

            $cart = $this->cart->updateItemInCart(key($previousItem), $attributes);
        }

        if ($addOriginalBox) {
            $cart->applyAction([
                'group' => CartEnum::ORIGINAL_BOX_COST->value,
                'id' => 1,
                'title' => 'Original Box Cost',
                'value' => $attributes['extra_info']['box_price'] * $attributes['quantity'],
                'rules' => [
                    'enable' => true
                ]
            ]);

        } else {
            $cart->clearActions();
        }

        return $cart;
    }


    public function removeItemFromCart($identifier): bool
    {
        $bool = true;
        try {
            $this->cart->removeItemFromCart($identifier);
            session()->put(request()->cookie('phone_number') . '_items_in_cart', $this->cart->getCartDetails()->quantities_sum);
        } catch (Exception $exception) {
            log_error(exception: $exception, abort: false);
            $bool = false;
        }
        return $bool;
    }

    public function fetchItemsInCart()
    {
        return $this->cart->getCartDetails();
    }

    public function fetchItemInCart($identifier): array
    {
        $previousItem = $this->cart->getItemInCart(['id' => $identifier]);
        $bool = false;

        if (!empty($previousItem)) {
            $bool = true;
            $previousItem = $this->cart->getOneItemInCart(key($previousItem));
        }

        return [
            "status" => $bool,
            "items" => $previousItem,
        ];
    }

    public function clearCart(): bool
    {
        $bool = true;
        try {
            $this->cart->clearCart();
            session()->forget(request()->cookie('phone_number') . '_items_in_cart');
        } catch (Exception $exception) {
            log_error(exception: $exception, abort: false);
            $bool = false;
        }
        return $bool;
    }

    public function checkout($addressId, $userId, array $senderInfo = []): array
    {

        $cartItems = $this->fetchItemsInCart();

        $buyerAddress = $this->fetchBuyerAddress($addressId, $userId);

        if (is_null($buyerAddress)) {
            return [
                "status" => false,
                "message" => "Please select a valid delivery address",
                "redirect_url" => null,
            ];
        }

        if ($cartItems->quantities_sum < 1) {
            return [
                "status" => false,
                "message" => "You have no item in your cart",
                "redirect_url" => null,
            ];
        }

        $message = "Hello, I would like to purchase the following:";
        foreach ($cartItems->items as $cartItem) {

            $image = $cartItem->extra_info->images;
            $route = route("dashboard.products.product", $cartItem->extra_info->productId);
            $message .= "\n\n*PRODUCT INFORMATION* \n";
            $message .= "Product Name: {$cartItem->title}\n";
            $message .= "Product ID: {$cartItem->extra_info->productId}\n";
            $message .= "Quantity: {$cartItem->quantity}\n";
            $message .= "Product Image: {$image}\n";
            $message .= "Product Url: {$route}\n";
            $message .= "Unit Price: Rs.{$cartItem->price}\n";
            $message .= "Price Type: For {$cartItem->extra_info->price_type}\n";

            if ($cartItem->actions_count > 0) {
                $message .= "Include Original Box?: Yes\n";
                $message .= "Original Box Total Price: {$cartItem->actions_amount}\n";
            }
            $message .= "Subtotal: Rs.{$cartItem->subtotal}\n";
        }

        $message .= "\nTotal Price: Rs.{$cartItems->total}\n";
        $message .= "Tax: Rs.{$cartItems->tax_amount}\n";
        $message .= "Discount: Rs.0\n";

        //Delivery Information:
        $message .= "\n*Delivery Information* \n";
        $message .= "Name: {$buyerAddress->name}\n";
        $message .= "Address: {$buyerAddress->address}\n";
        $message .= "Landmark: {$buyerAddress->landmark}\n";
        $message .= "City: {$buyerAddress->city}\n";
        $message .= "State: {$buyerAddress->state}\n";
        $message .= "Pin Code: {$buyerAddress->pin_code}\n";
        $message .= "Email ID: {$buyerAddress->email_id}\n";
        $message .= "Contact Number: {$buyerAddress->contact_number}\n";

        //Optional Sender Info for resellers
        if (!empty($senderInfo)) {
            $message .= "\n*Sender Information* \n";
            if (array_key_exists('sender_name', $senderInfo)) {
                $message .= "Sender Name: {$senderInfo['sender_name']}\n";
            }
            if (array_key_exists('store_name', $senderInfo)) {
                $message .= "Store Name: {$senderInfo['store_name']}\n";
            }
            if (array_key_exists('sender_contact', $senderInfo)) {
                $message .= "Reseller Phone Number: {$senderInfo['sender_contact']}\n";
            }
        }

        $redirectUrl = "https://wa.me/" . config("app.admin_number") . "?text=" . urlencode($message);
        return [
            "status" => true,
            "message" => "Text Generated",
            "redirect_url" => $redirectUrl,
        ];

    }

    public function inquire($productId): array
    {
        $product = Product::query()->with(['media'])->findOrFail($productId);
        $message = "Hello, I would like to inquire about the following product:";
        $image = $product->getFirstMediaUrl() ?? "";
        $route = route("dashboard.products.product", $product->product_id);

        $message .= "\n\nProduct Name: {$product->name}\n";
        $message .= "Product ID: {$product->product_id}\n";
        $message .= "Quantity: {$product->quantity}\n";
        $message .= "Product Image: {$image}\n";
        $message .= "Product Url: {$route}\n";
        $message .= "Unit Price: Rs.{$product->offer_price}\n";

        $redirectUrl = "https://wa.me/" . config("app.admin_number") . "?text=" . urlencode($message);
        return [
            "status" => true,
            "message" => "Text Generated",
            "redirect_url" => $redirectUrl,
        ];
    }

    public function fetchBuyerAddresses($userId): Collection|array
    {
        return Address::query()->where("user_id", $userId)->get();
    }

    public function fetchBuyerAddress($addressId, $userId): Builder|Model|null
    {
        return Address::query()->where(function ($query) use ($userId, $addressId) {
            $query->where("id", $addressId)->where("user_id", $userId);
        })->first();
    }

    public function addAddress(array $data): bool
    {
        $bool = false;
        try {

            $data['user_id'] = getCookieUser()->id;
            Address::query()->create($data);
            $bool = true;

        } catch (Exception $exception) {
            log_error(exception: $exception, abort: false);
        }
        return $bool;

    }

    public function deleteAddress($addressId, $userId): array
    {
        $response = [
            "status" => false,
            "message" => "Something went wrong,please try again"
        ];

        try {

            $return = Address::query()->where(function ($query) use ($userId, $addressId) {
                $query->where("id", $addressId)->where("user_id", $userId);
            })->delete();

            if ($return) {
                $response['status'] = true;
                $response['message'] = "Address deleted";
            } else {
                $response['status'] = false;
                $response['message'] = "Unable to delete address";
            }

        } catch (Exception $exception) {
            log_error(exception: $exception, abort: false);
        }
        return $response;

    }
}
