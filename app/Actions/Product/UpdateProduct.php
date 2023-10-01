<?php

namespace App\Actions\Product;

use App\Models\Product;

class UpdateProduct
{
    public function handle(Product $product, array $update)
    {
        $product->update($update);
    }
}
