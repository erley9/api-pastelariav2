<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends BaseEloquentRepository
{
    protected $model = Product::class;

    public function getProduct($productId) {
        return $this->getById($productId);
    }

    public function getAllProducts() {
        return $this->getAll(
            [
                "id",
                "name",
                "price",
                "photo",
            ],
            "name",
            "asc"
        );
    }

    public function saveProduct($request) {
        return $this->create($request);
    }

    public function changeProduct($id, $request) {
        return $this->update($id, $request);
    }

    public function deleteProduct($productId) {
        return $this->delete($productId);
    }
}
