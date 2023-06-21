<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCreateUpdateProduct;
use App\Services\ProductService;
use DB;

class ProductController extends Controller
{
    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = $this->service->listProducts();
        } catch(Exception $e) {
            return response()->json($e->getMessage(),500);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully',
            'products' => $products->toArray()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $product = $this->service->createProduct($request);
        } catch(Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage(),500);
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Product Created Successfully',
            'product' => $product
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        try {
            $product = $this->service->productForId($product->id);
        } catch(Exception $e) {
            return response()->json($e->getMessage(),500);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product Found Successfully',
            'product' => $product
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        DB::beginTransaction();

        try {
            $product = $this->service->updateProduct($product->id, $request);
        } catch(Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage(),500);
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Update Product Successfully',
            'product' => $product->toArray()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();

        try {
            $product = $this->service->removeProduct($product->id);
        } catch(Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage()->message(),500);
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Product Deleted',
        ], 200);
    }
}
