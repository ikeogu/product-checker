<?php

namespace App\Http\Controllers;

use App\Modules\Product\Services\ProductVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProductVerificationController extends Controller
{

    public function __construct(
        public readonly ProductVerificationService $productVerificationService
    ) {}


    public function verify(Request $request)
    {
        // ✅ Validation
        $validated = $request->validate([
            'barcode' => 'nullable|string|max:255',
            'text'    => 'nullable|string|max:500',
            'code'    => 'nullable|string|max:500',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $payload = [];

        // ✅ 1. Barcode
        if (!empty($validated['barcode']) || !empty($validated['code'])) {
            $payload['barcode'] = $validated['barcode'] ?? $validated['code'];
        }


        // ✅ 2. Text
        if (!empty($validated['text'])) {
            $payload['text'] = $validated['text'];
        }

        // ✅ 3. Image
        if (!empty($validated['image'])) {
            $imagePath = $validated['image']->store('uploads/products', 'public');

            $payload['image_url'] = Storage::url($imagePath);
            $payload['image_base64'] = base64_encode(file_get_contents($validated['image']->getRealPath()));


        }

        // ✅ Handle empty request
        if (empty($payload)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No input provided. Please submit a barcode, text, or image.',
            ], 422);
        }

        // ✅ Delegate to Service
        return $this->productVerificationService->verify($payload);
    }
}
