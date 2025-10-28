<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Modules\Product\Resources\ProductResource;
use App\Modules\Product\Services\ProductService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends ApiController
{

    protected function __construct(
        protected readonly ProductService $productService
    ) {}

    /**
     * List products with optional search
     * @param Request $request
     * @response array< message: string, data: array{products: array}>
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if ($search = $request->get('q')) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('barcode', $search)
                ->orWhere('nafdac_number', $search);
        }

        $products = $query->paginate(15);

        return $this->successResponse('Products fetched successfully', [
            'products' => ProductResource::collection($products)->response()->getData(true),
        ], Response::HTTP_OK);
    }

    /**
     * Show product details
     * @param Product $product
     */
    public function show(Product $product)
    {
        return $this->successResponse('Product fetched successfully', [
            'product' => new ProductResource($product),
        ], Response::HTTP_OK);
    }

    /**
     * Create a new Product
     * @param Request $request
     * @response array< message: string, data: array{product: array}>
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'required|string|unique:products,barcode',
            'nafdac_number' => 'nullable|string|unique:products,nafdac_number',
            'description' => 'nullable|string',
        ]);

        $product = $this->productService->createProduct($data);

        return $this->successResponse('Product created successfully', [
            'product' => new ProductResource($product),
        ], Response::HTTP_CREATED);
    }

    /**
     * Update product details
     * @param Request $request
     * @param Product $product
     * @response array< message: string, data: array{product: array}>
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'barcode' => 'sometimes|required|string|unique:products,barcode,' . $product->id,
            'nafdac_number' => 'sometimes|nullable|string|unique:products,nafdac_number,' . $product->id,
            'description' => 'sometimes|nullable|string',
        ]);

        $product->update($data);

        return $this->successResponse('Product updated successfully', [
            'product' => new ProductResource($product),
        ], Response::HTTP_OK);
    }

    /**
     * Delete a product
     * @param Product $product
     * @response array< status: string, message: string>
     */
    public function destroy(Product $product)
    {

        $product->delete();

        return $this->successResponse('Product deleted successfully', [], Response::HTTP_OK);
    }
}
