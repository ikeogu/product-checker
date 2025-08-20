<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
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
        $data = $request->validate([
            'name' => 'required|string',
            'category' => 'nullable|string',
            'barcode' => 'nullable|string|unique:products',
            'nafdac_number' => 'nullable|string',
            'batch_number' => 'nullable|string',
            'expiry_date' => 'nullable|date',
            'manufacture_date' => 'nullable|date',
            'manufacturer' => 'nullable|string',
            'country_of_origin' => 'nullable|string',
            'image_url' => 'nullable|url',
            'status' => 'nullable|in:authentic,counterfeit,expired,pending',
            'description' => 'nullable|string',
        ]);

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

        $data = $request->validate([
            'name' => 'sometimes|string',
            'category' => 'nullable|string',
            'barcode' => "nullable|string|unique:products,barcode,{$id}",
            'nafdac_number' => 'nullable|string',
            'batch_number' => 'nullable|string',
            'expiry_date' => 'nullable|date',
            'manufacture_date' => 'nullable|date',
            'manufacturer' => 'nullable|string',
            'country_of_origin' => 'nullable|string',
            'image_url' => 'nullable|url',
            'status' => 'nullable|in:authentic,counterfeit,expired,pending',
            'description' => 'nullable|string',
        ]);

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
