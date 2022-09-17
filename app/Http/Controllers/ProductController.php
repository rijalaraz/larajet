<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __invoke()
    {
        $products = Product::inRandomOrder()
            ->whereActive(true)
            ->take(16)
            ->get();

        return Inertia::render('Product', [
            'products' => $products
        ]);
    }
}
