<?php

namespace App\Repositories;

use App\Models\Product;
use Darryldecode\Cart\Facades\CartFacade;

class CartRepository
{
    public function add(Product $product) {
        // assuming you have a Product model with id, name, description & price
        // generate a unique() row ID
        $rowId = $product->id;
        // the user ID to bind the cart contents or any string representing user identifier
        $userId = auth()->user()->id;
        // add the product to cart or add a cart item to a specific user
        CartFacade::session($userId)->add([
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
        $userId = auth()->user()->id;
        // Getting cart's contents for a specific user
        $items = CartFacade::session($userId)->getContent();
        return $items;
    }

    public function count() {
        return $this->content()->sum('quantity');
    }
}