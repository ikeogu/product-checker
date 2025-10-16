<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Modules\Product\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends ApiController
{

    protected function __construct(
        protected readonly ProductService $productService
    ) {}
    
    public function index(Request $request)
    {
        $query = Product::query();

        if ($search = $request->get('q')) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('barcode', $search)
                ->orWhere('nafdac_number', $search);
        }

        return response()->json($query->paginate(10));
    }

    // Show a product
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    // Store a new product (Admin)
    public function store(Request $request)
    {
        $data = $request->validate([]);

        // $data['added_by'] = auth()->id();

        $product = Product::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $product
        ], 201);
    }

    // Update product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([]);

        $product->update($data);

        return response()->json([
            'status' => 'success',
            'data' => $product
        ]);
    }

    // Delete product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['status' => 'success', 'message' => 'Product deleted']);
    }
}
