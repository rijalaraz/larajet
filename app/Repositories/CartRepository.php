<?php

namespace App\Repositories;

use App\Models\Product;
use Darryldecode\Cart\Facades\CartFacade;

class CartRepository
{
    private $userId;

    public function __construct($userId = null)
    {
        // the user ID to bind the cart contents or any string representing user identifier
        if ($userId) {
            $this->userId = $userId;
        } else {
            $this->userId = auth()->user()->id;
        }
    }

    public function add(Product $product) {
        // assuming you have a Product model with id, name, description & price
        // generate a unique() row ID
        $rowId = $product->id;
        // add the product to cart or add a cart item to a specific user
        CartFacade::session($this->userId)->add([
            'id' => $rowId,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'attributes' => array(),
            'associatedModel' => $product
        ]);
        return $this->count();
    }

    public function content() {
        // Getting cart's contents for a specific user
        $items = CartFacade::session($this->userId)->getContent();
        return $items;
    }

    public function count() {
        return $this->content()->sum('quantity');
    }

    public function increase($rowId) {
        CartFacade::session($this->userId)->update($rowId, [
            'quantity' => +1
        ]);
    }

    public function remove($rowId) {
        CartFacade::session($this->userId)->remove($rowId);
    }

    public function decrease($rowId) {
        $item = CartFacade::session($this->userId)->get($rowId);
        if ($item->quantity === 1) {
            $this->remove($rowId);
            return;
        }

        CartFacade::session($this->userId)->update($rowId, [
            'quantity' => -1
        ]);
    }

    public function total()
    {
        return CartFacade::session($this->userId)->getTotal();
    }

    public function jsonOrderItems()
    {
        return $this
            ->content()
            ->map(function($item) {
                return [
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            })
            ->toJson();
    }

    public function clear()
    {
        CartFacade::session($this->userId)->clear();
    }
}