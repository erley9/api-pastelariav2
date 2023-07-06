<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;
use Exception;

class ProductController extends Controller
{
    public function __construct(protected ProductService $service)
    {
    }

    /**
     *
     * Busca todos os produtos cadastrados.
     *
     *@OA\Get(
     *   path="/api/product",
     *   tags={"Listagem de Produtos"},
     *   security={ {"bearerAuth": {}} },
     *   @OA\Response(
     *     response=200,
     *     description="Response Successful",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(property="status",type="string",example="true"),
     *         @OA\Property(property="message",type="string",example="Successfully"),
     *         @OA\Property(
     *          property="products",
     *          type="array",
     *            @OA\Items(
     *              type="object",
     *              @OA\Property(property="id",type="integer",example="1"),
     *              @OA\Property(property="name",type="string",example="Pastel de Carne"),
     *              @OA\Property(property="price",type="decimal",example="12.50"),
     *              @OA\Property(property="photo",type="string",example="https://via.placeholder.com/640x480.png/00ffaa?text=pasty+magnam")
     *            )
     *          )
     *        )
     *      )
     *    )
     * )
    **/
    public function index()
    {
        try {
            $products = $this->service->listProducts();
        } catch(Exception $e) {
            return response()->json($e->getMessage(), 500);
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
            return response()->json($e->getMessage(), 500);
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Product Created Successfully',
            'product' => $product
        ], 200);
    }

    /**
     *
     * Busca produto pela id.
     *
     *@OA\Get(
     *   path="/api/product/{id}",
     *   tags={"Listagem de Produtos"},
     *   @OA\Parameter(name="id", in="path",required=true, @OA\Schema(type="integer")),
     *   security={ {"bearerAuth": {}} },
     *   @OA\Response(
     *     response=200,
     *     description="Response Successful",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(property="status",type="string",example="true"),
     *         @OA\Property(property="message",type="string",example="Successfully"),
     *         @OA\Property(
     *          property="product",
     *          type="object",
     *              @OA\Property(property="id",type="integer",example="1"),
     *              @OA\Property(property="name",type="string",example="Pastel de Carne"),
     *              @OA\Property(property="price",type="decimal",example="12.50"),
     *              @OA\Property(property="photo",type="string",example="https://via.placeholder.com/640x480.png/00ffaa?text=pasty+magnam")*
     *          )
     *        )
     *      )
     *    )
     * )
    **/
    public function show(Product $product)
    {
        try {
            $product = $this->service->productForId($product->id);
        } catch(Exception $e) {
            return response()->json($e->getMessage(), 500);
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
            return response()->json($e->getMessage(), 500);
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
            return response()->json($e->getMessage(), 500);
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Product Deleted',
        ], 200);
    }
}
