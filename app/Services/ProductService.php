<?php
namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;

class ProductService
{
    public function __construct(ProductRepository $productRepository)
    {
        $this->repository = $productRepository;
    }

    public function uploadPhoto($request)
    {
        $newimagem = md5(uniqid()) . '-' . time() . '.png';
        $path = $request->file('photo')->move(public_path("/photos"), $newimagem);
        $photoUrl = url('/photos/'.$newimagem);
        return $photoUrl;
    }

    public function deleteOldPhoto($productId)
    {
        $product = $this->repository->getProduct($productId);
        $photo = str_replace(url("/photos"),'',$product->photo);
        $filename = public_path("/photos").$photo;
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    public function getValidator($request, $action = 'create')
    {
        $rulesValidation = [
            'name' => 'required',
            'price' => 'required|decimal:2',

        ];

        if ($action == 'create' || ($action != 'create' && $request->file('photo') != null)) {
            $rulesValidation["photo"] = [
                'required',
                'image',
                File::types(['jpg', 'png'])
            ];
        }

        return Validator::make($request->all(), $rulesValidation);
    }

    public function listProducts()
    {
        return $this->repository->getAllProducts();
    }

    public function productForId($productId)
    {
        return $this->repository->getProduct($productId);
    }

    public function createProduct($request)
    {
        $validator = $this->getValidator($request);

        if ($validator->fails()) {
            return response()->json([
                "error" => 'validation_error',
                "message" => $validator->errors(),
            ], 422);
        }

        $data = [
            'name' => $request->name,
            'price' => $request->price
        ];

        $data["photo"] = $this->uploadPhoto($request);

        return $this->repository->saveProduct($data);
    }

    public function updateProduct($productId, $request)
    {
        $validator = $this->getValidator($request, 'update');

        if ($validator->fails()) {
            return response()->json([
                "error" => 'validation_error',
                "message" => $validator->errors(),
            ], 422);
        }

        $data = [
            'name' => $request->name,
            'price' => $request->price
        ];

        if($request->file('photo') != null) {
            $data['photo'] = $this->uploadPhoto($request);
            $this->deleteOldPhoto($productId);
        }

        $this->repository->changeProduct($productId, $data);

        return $this->repository->getById($productId);
    }

    public function removeProduct($productId) {
        $this->repository->deleteProduct($productId);
    }
}
