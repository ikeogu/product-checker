<?php

namespace App\Modules\Product\Services;

use App\Models\Product;

class ProductService {


    public function createProduct(array $data) {
        // Business logic for creating a product can be added here
        return Product::create($data);
    }

    public function updateProduct(Product $product, array $data) {
        // Business logic for updating a product can be added here
        $product->update($data);
        return $product;
    }

    public function deleteProduct(Product $product) {
        // Business logic for deleting a product can be added here
        return $product->delete();
    }
}
