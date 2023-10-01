<?php

namespace App\Actions\Product;

use App\Models\Product;

class CreateProduct
{
    public function handle(array $product): Product
    {
        return Product::create([
            'name' => $product['name'],
            'category_id' => $product['category_id'],
            'price' => number_format($product['price'], 2),
        ]);
    }
}
